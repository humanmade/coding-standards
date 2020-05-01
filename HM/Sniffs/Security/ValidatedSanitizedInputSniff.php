<?php

namespace HM\Sniffs\Security;

use HM\Sniffs\ExtraSniffCode;
use PHP_CodeSniffer\Files\File as PhpcsFile;
use WordPressCS\WordPress\Sniffs\Security\ValidatedSanitizedInputSniff as WPCSValidatedSanitizedInputSniff;

class ValidatedSanitizedInputSniff extends WPCSValidatedSanitizedInputSniff {
	use ExtraSniffCode;

	/**
	 * Keys to allow in the $_SERVER variable.
	 *
	 * Set this to override.
	 *
	 * @var array
	 */
	public $allowedServerKeys = [
		'HTTP_HOST',

		// User-Agent is forced to a static value when passing through
		// CloudFront, so is safe to use.
		'HTTP_USER_AGENT',

		'HTTPS',
		'REMOTE_ADDR',
		'REQUEST_METHOD',
		'REQUEST_TIME',
		'REQUEST_TIME_FLOAT',
		'REQUEST_URI',
		'QUERY_STRING',
		'SERVER_ADDR',
	];

	/**
	 * Override init to duplicate any ignores.
	 *
	 * @param PhpcsFile $phpcsFile
	 */
	protected function init( PhpcsFile $phpcsFile ) {
		parent::init( $phpcsFile );

		$this->duplicate_ignores( 'WordPress.Security.ValidatedSanitizedInput' );
	}

	/**
	 * Process a token for validation and sanitisation.
	 *
	 * @param int $stackPtr
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		// Process our custom server rules first.
		if ( $this->tokens[ $stackPtr ]['content'] === '$_SERVER' ) {
			$pass = $this->check_server_variable( $stackPtr );
			if ( $pass ) {
				// Variable is fine, skip upstream checks.
				return;
			}
		}

		// Not an allowed usage, so run the regular check on it.
		return parent::process_token( $stackPtr );
	}

	/**
	 * Check whether a $_SERVER variable is constant and allowed.
	 *
	 * @param int $stackPtr Current token to check.
	 * @return bool True if this is a $_SERVER variable and is safe, false to run regular checks.
	 */
	protected function check_server_variable( $stackPtr ) {
		$key = $this->get_array_access_key( $stackPtr );

		// Find the next non-whitespace token.
		$open_bracket = $this->phpcsFile->findNext( T_WHITESPACE, ( $stackPtr + 1 ), null, true );
		if ( $this->tokens[ $open_bracket ]['code'] !== T_OPEN_SQUARE_BRACKET ) {
			// No index access, run regular checks.
			return false;
		}

		$index_token = $this->phpcsFile->findNext( T_WHITESPACE, ( $open_bracket + 1 ), null, true );
		if ( $this->tokens[ $index_token ]['code'] !== T_CONSTANT_ENCAPSED_STRING ) {
			// Dynamic string, run regular checks.
			return false;
		}

		// Possible constant string, check there's no further dynamic parts.
		$maybe_close_bracket = $this->phpcsFile->findNext( T_WHITESPACE, ( $index_token + 1 ), null, true );
		if ( $this->tokens[ $maybe_close_bracket ]['code'] !== T_CLOSE_SQUARE_BRACKET ) {
			// Dynamic string, run regular checks.
			return false;
		}

		// Constant string, check if it's allowed.
		$key = $this->strip_quotes( $this->tokens[ $index_token ]['content'] );
		if ( ! in_array( $key, $this->allowedServerKeys, true ) ) {
			// Unsafe key, requires sanitising.
			return false;
		}

		// Safe key, allow it.
		return true;
	}
}
