<?php
namespace HM\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Namespaced things must be in directories matching the namespace.
 */
class NamespaceDirectoryNameSniff implements Sniff {
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array( T_NAMESPACE );
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the
	 *                        stack passed in $tokens.
	 *
	 * @return int
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$namespace = '';

		$name_ptr = $phpcsFile->findNext( T_STRING, 0);
		if ( ! $name_ptr ) {
			// Non-namespaced, skip check.
			return;
		}

		do {
			$namespace .= $tokens[ $name_ptr ]['content'];
			$name_ptr++;
		} while ( in_array( $tokens[ $name_ptr ]['code'], [ T_STRING, T_NS_SEPARATOR ] ) );

		$full = $phpcsFile->getFileName();
		$filename = basename( $full );
		$directory = dirname( $full );

		// Normalize the directory separator across operating systems
		if ( DIRECTORY_SEPARATOR !== '/' ) {
			$directory = str_replace( DIRECTORY_SEPARATOR, '/', $directory );
		}

		if ( $filename === 'plugin.php' || $filename === 'functions.php' || $filename === 'load.php' ) {
			// Ignore the main file.
			return;
		}

		if ( ! preg_match( '#(?:.*)(?:/inc|/tests)(/.*)?#', $directory, $matches ) ) {
			$error = 'Namespaced classes and functions should live inside an inc directory.';
			$phpcsFile->addError( $error, $stackPtr, 'NoIncDirectory' );
			return;
		}

		// Find correct after namespace-base path.
		$after_dir = $matches[1] ?? '';

		if ( empty( $after_dir ) ) {
			// Base inc directory, skip checks.
			return;
		}

		$namespace_parts = explode( '\\', $namespace );
		$directory_parts = explode( '/', trim( $after_dir, '/' ) );

		// Check that the path matches the namespace, allowing parts to be dropped.
		while ( ! empty( $directory_parts ) ) {
			$dir_part = array_pop( $directory_parts );
			$ns_part = array_pop( $namespace_parts );
			if ( empty( $ns_part ) ) {
				// Ran out of namespace, but directory still has parts.
				$error = 'Directory %s for namespace %s found; nested too deep.';
				$error_data = [ $after_dir, $namespace ];
				$phpcsFile->addError( $error, $stackPtr, 'ExtraDirs', $error_data );
				return;
			}

			if ( strtolower( $ns_part ) !== $dir_part ) {
				$error = 'Directory %s for namespace %s found; use %s instead';
				$error_data = [ $dir_part, $namespace, strtolower( $ns_part ) ];
				$phpcsFile->addError( $error, $stackPtr, 'NameMismatch', $error_data );
				return;
			}
		}
	}
}
