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
	 * Path to default configuration.
	 */
	const DEFAULT_CONFIG = 'vendor/humanmade/coding-standards/.eslintrc.yml';

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var array
	 */
	public $supportedTokenizers = [ 'JS' ];

	/**
	 * ESLint configuration file path.
	 *
	 * @var string|null Path to eslintrc. Null to autodetect.
	 */
	public $configFile = null;

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
		$config_file = $this->configFile;
		if ( empty( $config_file ) ) {
			// Attempt to autodetect.
			$candidates = glob( '.eslintrc{.js,.yaml,.yml,.json}', GLOB_BRACE );
			if ( ! empty( $candidates ) ) {
				$config_file = $candidates[0];
			} else {
				$config_file = static::DEFAULT_CONFIG;
			}
		}

		$eslint_options = [
			sprintf( '--config %s', $config_file ),
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
