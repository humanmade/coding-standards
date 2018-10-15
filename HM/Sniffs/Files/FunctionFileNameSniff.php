<?php
namespace HM\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Sniff to check for namespaced functions are in `namespace.php`.
 */
class FunctionFileNameSniff implements Sniff {
	public function register() {
		return array( T_FUNCTION );
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		if ( $tokens[ $stackPtr ]['level'] !== 0 ) {
			// Ignore methods.
			return;
		}

		$namespace = $phpcsFile->findNext( T_NAMESPACE , 0);
		if ( empty( $namespace ) ) {
			// Non-namespaced function.
			return;
		}

		$filename = basename( $phpcsFile->getFileName() );

		if ( $filename === 'namespace.php' ) {
			return;
		}

		// Get the trailing part of the namespace to match it against the file name.
		$trailing_namespace = $tokens[ $phpcsFile->findPrevious( T_STRING, $phpcsFile->findNext( T_SEMICOLON, $namespace ) ) ];
		$expected_filename = str_replace( '_', '-', strtolower( $trailing_namespace['content'] ) ) . '.php';
		if ( $filename === $expected_filename ) {
			return;
		}

		$error = 'Namespaced functions must be in namespace.php or $namespace.php';
		$phpcsFile->addError($error, $stackPtr, 'WrongFile');
	}
}
