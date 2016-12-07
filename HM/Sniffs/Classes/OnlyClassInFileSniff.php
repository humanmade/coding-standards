<?php

namespace HM\Sniffs\Classes;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_Tokens;

/**
 * Sniff to check classes are by themselves.
 */
class OnlyClassInFileSniff implements PHP_CodeSniffer_Sniff {
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [ T_OPEN_TAG ];
	}

	/**
	 * Processes this sniff, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token in
	 *                                        the token stack.
	 *
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$error = 'Files should only contain a single class, and no other declarations. First class was defined on line %s, and a %s was found on line %s.';

		$tokens = $phpcsFile->getTokens();

		$classish = [ T_CLASS, T_INTERFACE, T_TRAIT ];
		$first_class = $phpcsFile->findNext( $classish, 0 );
		if ( empty( $first_class ) ) {
			// No class in file.
			return;
		}

		// Check for classes first...
		$other_declaration = $phpcsFile->findNext( $classish, $first_class + 1 );
		if ( empty( $other_declaration ) ) {
			// ...then check for functions at the top-level.
			$other_declaration_start = 0;
			do {
				$other_declaration = $phpcsFile->findNext( [ T_FUNCTION ], $other_declaration_start );
				$other_declaration_start = $other_declaration + 1;
			} while ( $other_declaration && $tokens[ $other_declaration ]['level'] > 0 );
		}

		if ( ! empty( $other_declaration ) ) {
			$data = [
				$tokens[ $first_class ]['line'],
				$tokens[ $other_declaration ]['content'],
				$tokens[ $other_declaration ]['line'],
			];
			$phpcsFile->addWarning($error, 0, 'FoundMultipleDeclarations', $data);
			$phpcsFile->recordMetric($stackPtr, 'Multiple declarations', 'yes');
		} else {
			$phpcsFile->recordMetric($stackPtr, 'Multiple declarations', 'no');
		}

		// Ignore the rest of the file.
		return $phpcsFile->numTokens + 1;
	}
}
