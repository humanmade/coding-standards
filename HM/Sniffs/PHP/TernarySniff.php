<?php
namespace HM\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Sniff to check for ternary usage.
 */
class TernarySniff implements Sniff {
	public function register() {
		return array( T_INLINE_ELSE );
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$inline_then_token = $phpcsFile->findPrevious( T_INLINE_THEN, $stackPtr );

		$value_if_true_tokens = $this->get_nonempty_tokens( $tokens, $inline_then_token + 1, $stackPtr - 1 );
		if ( count( $value_if_true_tokens ) !== 1 ) {
			// No single value.
			return;
		}

		if ( ! $this->is_boolean_token( $value_if_true_tokens[0] ) ) {
			// No Boolean value.
			return;
		}

		$ternary_end_token = $phpcsFile->findNext( array( T_CLOSE_CURLY_BRACKET, T_CLOSE_PARENTHESIS, T_SEMICOLON ), $stackPtr + 1 );

		$value_if_false_tokens = $this->get_nonempty_tokens( $tokens, $stackPtr + 1, $ternary_end_token - 1 );
		if ( count( $value_if_false_tokens ) !== 1 ) {
			// No single value.
			return;
		}

		if ( ! $this->is_boolean_token( $value_if_false_tokens[0] ) ) {
			// No Boolean value.
			return;
		}

		$warning = 'Unnecessary ternary found: Instead of "$expr ? %s : %s", use "%s"';
		$data = [
			$value_if_true_tokens[0]['content'],
			$value_if_false_tokens[0]['content'],
			$value_if_true_tokens[0]['content'] === 'true' ? '(bool) $expr' : '! $expr'
		];
		$phpcsFile->addWarning( $warning, $stackPtr, 'UnnecessaryTernary', $data );
	}

	private function get_nonempty_tokens( array $tokens, $start, $end ) {
		$tokens = array_slice( $tokens, $start, $end - $start + 1 );

		return array_values( array_filter(
			$tokens,
			[ $this, 'is_nonempty_token' ]
		) );
	}

	private function is_boolean_token( array $token ) {
		return in_array( $token['code'], [ T_FALSE, T_TRUE ], true );
	}

	private function is_nonempty_token( array $token ) {
		return ! in_array( $token['code'], Tokens::$emptyTokens, true );
	}
}
