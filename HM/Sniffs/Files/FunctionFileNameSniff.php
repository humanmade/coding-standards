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
		if ( $filename !== 'namespace.php' ) {
			$error = 'Namespaced functions must be in namespace.php';
			$phpcsFile->addError($error, $stackPtr, 'WrongFile');
		}
	}
}
