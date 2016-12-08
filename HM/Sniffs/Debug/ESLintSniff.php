<?php

namespace HM\Sniffs\Debug;

use PHP_CodeSniffer;
use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;

/**
 * Run ESLint on the file.
 */
class ESLintSniff implements PHP_CodeSniffer_Sniff {
	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var array
	 */
	public $supportedTokenizers = [ 'JS' ];

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return int[]
	 */
	public function register() {
		return array(T_OPEN_TAG);
	}

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
	 * @param int                  $stackPtr  The position in the stack where
	 *                                        the token was found.
	 *
	 * @return void
	 * @throws PHP_CodeSniffer_Exception If jslint.js could not be run
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$filename = $phpcsFile->getFilename();
		$eslint_path = PHP_CodeSniffer::getConfigData( 'eslint_path' );
		if ( $eslint_path === null ) {
			return;
		}
		$eslint_options = [
			'--config vendor/humanmade/coding-standards/.eslintrc.yml',
			'--format json',
		];

		$cmd = sprintf(
			'"%s" %s "%s"',
			$eslint_path,
			implode( ' ', $eslint_options ),
			$filename
		);
		$descriptors = [
			0 => [ 'pipe', 'r' ],
			1 => [ 'pipe', 'w' ],
			2 => [ 'pipe', 'w' ],
		];
		$process = proc_open( $cmd, $descriptors, $pipes );

		// Ignore stdin.
		fclose( $pipes[0] );
		$stdout = stream_get_contents( $pipes[1] );
		$stderr = stream_get_contents( $pipes[2] );
		fclose( $pipes[1] );
		fclose( $pipes[2] );

		// Close, and start working!
		$code = proc_close( $process );

		if ( $code > 0 ) {
			$data = json_decode( $stdout );
			// Detect errors:
			if ( json_last_error() !== JSON_ERROR_NONE ) {
				$error = 'Unable to run eslint: %s';
				$phpcsFile->addError( $error, $stackPtr, 'CouldNotStart', [ $stdout ] );
			} else {
				// Data is a list of files, but we only pass a single one.
				$messages = $data[0]->messages;
				foreach ( $messages as $error ) {
					if ( ! empty( $error->fatal ) || $error->severity === 2 ) {
						$phpcsFile->addErrorOnLine( $error->message, $error->line, $error->ruleId );
					} else {
						$phpcsFile->addWarningOnLine( $error->message, $error->line, $error->ruleId );
					}
				}
			}
		}

		// Ignore the rest of the file.
		return ($phpcsFile->numTokens + 1);
	}
}
