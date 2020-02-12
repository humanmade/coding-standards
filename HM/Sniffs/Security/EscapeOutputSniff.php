<?php

namespace HM\Sniffs\Security;

use WordPress\Sniffs\Security\EscapeOutputSniff as WPCSEscapeOutputSniff;
use WordPressCS\WordPress\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Verify all strings are escaped.
 *
 * This is subclassed from WPCS, as we need to disable warnings for error
 * logging, as we don't output those to the page.
 *
 * @see https://github.com/WordPress/WordPress-Coding-Standards/issues/1864
 */
class EscapeOutputSniff extends WPCSEscapeOutputSniff {
	/**
	 * Allowed functions which are treated by WPCS as printing functions.
	 *
	 * @var array
	 */
	protected $hmSafePrintingFunctions = [
		'_deprecated_argument' => true,
		'_deprecated_constructor' => true,
		'_deprecated_file' => true,
		'_deprecated_function' => true,
		'_deprecated_hook' => true,
		'_doing_it_wrong' => true,
		'trigger_error' => true,
		'user_error' => true,
	];

	/**
	 * Constructor.
	 *
	 * Removes non-printing functions from the property.
	 */
	public function __construct() {
		// Remove error logging functions from output functions.
		foreach ( $this->hmSafePrintingFunctions as $function => $val ) {
			unset( $this->printingFunctions[ $function ] );
		}
	}
}
