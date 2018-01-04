<?php
namespace HM\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Sniff to check for namespaces for functions.
 */
class NamespacedFunctionsSniff implements Sniff {
	public function register() {
		return array( T_FUNCTION );
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		if (isset($tokens[$stackPtr]['scope_closer']) === false) {
			return;
		}

		$errorData = array(strtolower($tokens[$stackPtr]['content']));
		$namespace = $phpcsFile->findNext(array(T_NAMESPACE, T_FUNCTION), 0);
		if ($tokens[$namespace]['code'] !== T_NAMESPACE) {
			$error = 'Each %s must be in a namespace of at least one level (a top-level vendor name)';
			$phpcsFile->addError($error, $stackPtr, 'MissingNamespace', $errorData);
			$phpcsFile->recordMetric($stackPtr, 'Function defined in namespace', 'no');
		} else {
			$phpcsFile->recordMetric($stackPtr, 'Function defined in namespace', 'yes');
		}
	}
}
