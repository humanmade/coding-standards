<?php
namespace HM\Sniffs\PHP;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Sniff to check for isset() usage.
 */
class IssetSniff implements Sniff {
	public function register() {
		return array( T_ISSET );
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$open_parenthesis_token = $phpcsFile->findNext( T_OPEN_PARENTHESIS, $stackPtr + 1 );
		if ( $open_parenthesis_token === false ) {
			throw new RuntimeException( '$stackPtr was not a valid T_ISSET' );
		}

		$comma_token = $phpcsFile->findNext( T_COMMA, $open_parenthesis_token + 1 );
		if ( $comma_token !== false && $comma_token < $tokens[ $open_parenthesis_token ]['parenthesis_closer'] ) {
			$phpcsFile->addWarning( 'Only one argument should be used per ISSET call', $stackPtr, 'MultipleArguments' );
		}
	}
}
