<?php

namespace HM\Sniffs;

use PHP_CodeSniffer\Util;

trait ExtraSniffCode {
	/**
	 * Duplicates ignore statements from a legacy sniff.
	 *
	 * This allows overriding an existing sniff and retaining the existing
	 * ignore statements.
	 *
	 * @param string $legacy Legacy sniff code
	 */
	protected function duplicate_ignores( $legacy ) {
		$expression = sprintf( '/^%s(\..+)?$/', preg_quote( $legacy ) );
		$base_code = Util\Common::getSniffCode( get_class( $this ) );

		foreach ( $this->phpcsFile->tokenizer->ignoredLines as $line => $ignored ) {
			$additional = [];

			if ( empty( $ignored ) ) {
				continue;
			}

			// Find any code which matches the legacy value.
			foreach ( $ignored as $code => $value ) {
				if ( preg_match( $expression, $code, $matches ) ) {
					// Duplicate as the new code.
					$new_code = $base_code;
					if ( ! empty( $matches[1] ) ) {
						$new_code .= $matches[1];
					}

					$additional[ $new_code ] = $value;
				}
			}

			if ( ! empty( $additional ) ) {
				$this->phpcsFile->tokenizer->ignoredLines[ $line ] = array_merge( $ignored, $additional );
			}
		}

	}
}
