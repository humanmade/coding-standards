<?php
/**
 * Sniff verifying that theme pattern slugs match their filenames.
 */

namespace HM\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Flags theme pattern files whose Slug: header does not end with the file's basename.
 *
 * A pattern with `Slug: my-theme/hero-banner` must live in `hero-banner.php`.
 */
class PatternSlugMatchesFilenameSniff implements Sniff {

	/**
	 * Register for the open tag so the check runs once per file.
	 *
	 * @return array<int|string>
	 */
	public function register() : array {
		return [ T_OPEN_TAG ];
	}

	/**
	 * Compare the trailing segment of the pattern's Slug header to the filename.
	 *
	 * @param File $phpcs_file The file being scanned.
	 * @param int  $stack_ptr  Position of the open tag.
	 * @return int Pointer past the end of the file, so the sniff runs only once.
	 */
	public function process( File $phpcs_file, $stack_ptr ) {
		if ( ! preg_match( '#/themes/[^/]+/patterns/([^/]+)\.php$#', $phpcs_file->getFilename(), $matches ) ) {
			return $phpcs_file->numTokens;
		}
		$filename = $matches[1];

		foreach ( $phpcs_file->getTokens() as $ptr => $token ) {
			if ( T_DOC_COMMENT_STRING !== $token['code'] ) {
				continue;
			}
			if ( ! preg_match( '#^Slug:\s*(\S+)#', trim( $token['content'] ), $slug_match ) ) {
				continue;
			}

			$slug = $slug_match[1];
			if ( basename( $slug ) !== $filename ) {
				$phpcs_file->addError(
					'Pattern slug "%s" does not match the filename; expected the file to be named "%s.php".',
					$ptr,
					'SlugMismatch',
					[ $slug, basename( $slug ) ]
				);
			}
			break;
		}

		return $phpcs_file->numTokens;
	}
}
