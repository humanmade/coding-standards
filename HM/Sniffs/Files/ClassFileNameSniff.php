<?php
namespace HM\Sniffs\Files;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;

/**
 * Filenames with classes must start with class- and be the class name.
 */
class ClassFileNameSniff implements PHP_CodeSniffer_Sniff {
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array( T_CLASS, T_INTERFACE, T_TRAIT );
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token in the
	 *                                        stack passed in $tokens.
	 *
	 * @return int
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$namespace_ptr = $phpcsFile->findNext(T_NAMESPACE, 0);
		if ( ! $namespace_ptr ) {
			// Non-namespaced, skip check.
			return;
		}

		$class_name_ptr = $phpcsFile->findNext( T_STRING, $stackPtr );

		$class_name = $tokens[ $class_name_ptr ]['content'];

		// Build a filename from the class name.
		$class_slug = str_replace( '_', '-', strtolower( $class_name ) );
		$expected_filename = 'class-' . $class_slug . '.php';

		$filename = basename( $phpcsFile->getFileName() );
		if ( $filename !== $expected_filename ) {
			$error = 'Filename %s for class %s found; use %s instead';
			$phpcsFile->addError( $error, $stackPtr, 'MismatchedName', [ $filename, $class_name, $expected_filename ] );
		}
	}
}
