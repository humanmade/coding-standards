<?php

namespace HM\Sniffs\Arrays;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_Tokens;

class ForceShortArraysSniff implements PHP_CodeSniffer_Sniff {
	/**
	 * Registers the tokens that this sniff wants to listen for.
	 *
	 * @return int[]
	 */
	public function register() {
		return [ T_ARRAY ];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token
	 *                                        in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$error = 'Long-style array syntax is not allowed';
		$fix = $phpcsFile->addError( $error, $stackPtr, 'Found' );
	}
}
