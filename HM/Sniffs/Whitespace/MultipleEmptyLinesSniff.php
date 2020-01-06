<?php
/**
 * Check multiple consecutive newlines in a file.
 */

namespace HM\Sniffs\Whitespace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class that checks for more than one consecutive empty line.
 *
 * This sniff is adapted from the MediaWiki Tools ruleset
 * See: https://github.com/wikimedia/mediawiki-tools-codesniffer/commit/3a6709be2612fad63b9e9ead4e6644c28748edcc#diff-4522e134348f6d4e2efe9ccb7148a254
 */
class MultipleEmptyLinesSniff implements Sniff {

	public function register() {
		return [ T_WHITESPACE ];
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if (
			$stackPtr > 2
			&& $tokens[ $stackPtr - 1 ]['line'] < $tokens[ $stackPtr ]['line']
			&& $tokens[ $stackPtr - 2 ]['line'] === $tokens[ $stackPtr - 1 ]['line']
		) {
			// This is the first whitespace token on a line
			// and the line before this one is not empty,
			// so this could be the start of a multiple empty line block.
			$next = $phpcsFile->findNext( T_WHITESPACE, $stackPtr, null, true );
			$lines = ( $tokens[ $next ]['line'] - $tokens[ $stackPtr ]['line'] );

			if ( $lines > 1 ) {
				// If the next non T_WHITESPACE token is more than 1 line away,
				// then there were multiple empty lines.
				$error = 'Multiple empty lines should not exist in a row; found %s consecutive empty lines';
				$fix   = $phpcsFile->addFixableError(
					$error,
					$stackPtr,
					'MultipleEmptyLines',
					[ $lines ]
				);

				if ( $fix === true ) {
					$phpcsFile->fixer->beginChangeset();
					$i = $stackPtr;
					while ( $tokens[ $i ]['line'] !== $tokens[ $next ]['line'] ) {
						$phpcsFile->fixer->replaceToken( $i, '' );
						$i++;
					}
					$phpcsFile->fixer->addNewlineBefore( $i );
					$phpcsFile->fixer->endChangeset();
				}
			}
		}
	}
}
