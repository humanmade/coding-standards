<?php
namespace HM\Sniffs\Files;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;

/**
 * Sniff to check for namespaced functions are in `namespace.php`.
 */
class FunctionFileNameSniff implements PHP_CodeSniffer_Sniff {
	public function register() {
		return array( T_FUNCTION );
	}

	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
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
		if ( $filename !== 'namespace.php' ) {
			$error = 'Namespaced functions must be in namespace.php';
			$phpcsFile->addError($error, $stackPtr, 'WrongFile');
		}
	}
}
