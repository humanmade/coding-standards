<?php

namespace HM\Sniffs\Security;

use HM\Sniffs\ExtraSniffCode;
use PHP_CodeSniffer\Files\File as PhpcsFile;
use WordPressCS\WordPress\Sniffs\Security\NonceVerificationSniff as WPCSNonceVerificationSniff;

/**
 * Checks that nonce verification accompanies form processing.
 *
 * This is subclassed from WPCS to allow `$_GET` variables to be used if
 * configured to do so.
 */
class NonceVerificationSniff extends WPCSNonceVerificationSniff {
	use ExtraSniffCode;

	/**
	 * Allow query ($_GET) variables to be used without checking nonces?
	 *
	 * Nonces are designed to protect against destructive actions taking
	 * place without user intent. However, query variables are typically used
	 * for non-destructive actions, so this is a false positive in most cases.
	 *
	 * @var boolean
	 */
	public $allowQueryVariables = false;

	/**
	 * Override init to override config and duplicate any ignores.
	 *
	 * @param PhpcsFile $phpcsFile
	 */
	public function init( PhpcsFile $file ) {
		parent::init( $file );

		if ( $this->allowQueryVariables ) {
			unset( $this->superglobals[ '$_GET' ] );
		}

		$this->duplicate_ignores( 'WordPress.Security.NonceVerification' );
	}
}
