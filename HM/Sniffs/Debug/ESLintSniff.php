<?php

namespace HM\Sniffs\Debug;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Run ESLint on the file.
 */
class ESLintSniff implements Sniff {
	/**
	 * Path to default configuration.
	 */
	const DEFAULT_CONFIG = 'vendor/humanmade/coding-standards/packages/eslint-config-humanmade/.eslintrc';

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
	 * @param File $phpcsFile The file where the token was found.
	 * @param int  $stackPtr  The position in the stack where
	 *                                        the token was found.
	 *
	 * @return void
	 * @throws PHP_CodeSniffer_Exception If jslint.js could not be run
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$filename = $phpcsFile->getFilename();
		$eslint_path = Config::getConfigData( 'eslint_path' );
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
		$env = array_merge( $_ENV, [
			'NODE_PATH' => dirname( dirname( dirname( __DIR__ ) ) ) . '/packages',
		] );
		$process = proc_open( $cmd, $descriptors, $pipes, null, $env );

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
