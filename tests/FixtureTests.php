<?php
/**
 * Run tests on fixture files against our custom standards.
 *
 * This test suite runs our standards against files which have
 * known errors or known passing conditions. We run these tests
 * against said fixture files as it's closer to real-world conditions
 * than isolated unit tests and provides another layer of security.
 */

namespace HM\CodingStandards\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Files\LocalFile;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class FixtureTests
 *
 * @group fixtures
 */
class FixtureTests extends TestCase {
	/**
	 * Config instance.
	 *
	 * @var \PHP_CodeSniffer\Config
	 */
	protected $config;

	/**
	 * Ruleset instance.
	 *
	 * @var \PHP_CodeSniffer\Ruleset
	 */
	protected $ruleset;

	/**
	 * Get a lit of files from a directory path.
	 *
	 * @param string $directory Directory to recursively look through.
	 * @return array List of files to run.
	 */
	public static function get_files_from_dir( string $directory ) {
		$files = [];
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $directory )
		);

		foreach ( $iterator as $path => $file ) {
			if ( ! $file->isFile() || $file->getExtension() === 'json' ) {
				continue;
			}

			$files[] = [ $path ];
		}

		return $files;
	}

	/**
	 * Get files from the pass fixtures directory.
	 *
	 * @return array List of parameters to provide.
	 */
	public static function failing_files() {
		$directory = __DIR__ . '/fixtures/fail';

		return static::get_files_from_dir( $directory );
	}

	/**
	 * Get files from the pass fixtures directory.
	 *
	 * @return array List of parameters to provide.
	 */
	public static function passing_files() {
		$directory = __DIR__ . '/fixtures/pass';

		return static::get_files_from_dir( $directory );
	}

	/**
	 * Setup our ruleset.
	 */
	public function setUp() {
		$this->config            = new Config();
		$this->config->cache     = false;
		$this->config->standards = [ 'HM' ];

		// Keeping the tabWidth set inline with WPCS.
		// See: https://github.com/humanmade/coding-standards/pull/88#issuecomment-464076803
		$this->config->tabWidth = 4;

		// We want to setup our tests to only load our standards in for testing.
		$this->config->sniffs = [
			'HM.Classes.OnlyClassInFile',
			'HM.Debug.ESLint',
			'HM.Files.ClassFileName',
			'HM.Files.FunctionFileName',
			'HM.Files.NamespaceDirectoryName',
			'HM.Functions.NamespacedFunctions',
			'HM.Layout.Order',
			'HM.Namespaces.NoLeadingSlashOnUse',
			'HM.Performance.SlowMetaQuery',
			'HM.Performance.SlowOrderBy',
			'HM.PHP.Isset',
			'HM.Security.EscapeOutput',
			'HM.Security.NonceVerification',
			'HM.Security.ValidatedSanitizedInput',
			'HM.Whitespace.MultipleEmptyLines',
		];

		$this->ruleset = new Ruleset( $this->config );

		// Set configuration as needed too.
		$this->ruleset->setSniffProperty( 'HM\\Sniffs\\Security\\EscapeOutputSniff', 'customAutoEscapedFunctions', [
			'my_custom_func',
			'another_func',
		] );
		$this->ruleset->setSniffProperty( 'HM\\Sniffs\\Security\\NonceVerificationSniff', 'allowQueryVariables', true );
	}

	/**
	 * @dataProvider passing_files
	 */
	public function test_passing_files( $file ) {
		$phpcsFile = new LocalFile( $file, $this->ruleset, $this->config );
		$phpcsFile->process();

		$rel_file = substr( $file, strlen( __DIR__ ) );
		$foundErrors = $phpcsFile->getErrors();
		$this->assertEquals( [], $foundErrors, sprintf( 'File %s should not contain any errors', $rel_file ) );
		$foundWarnings = $phpcsFile->getWarnings();
		$this->assertEquals( [], $foundWarnings, sprintf( 'File %s should not contain any warnings', $rel_file ) );
	}

	/**
	 * @dataProvider failing_files
	 */
	public function test_failing_files( $file ) {
		$phpcsFile = new LocalFile( $file, $this->ruleset, $this->config );
		$phpcsFile->process();

		$rel_file = substr( $file, strlen( __DIR__ ) );
		$foundErrors = $phpcsFile->getErrors();
		$foundWarnings = $phpcsFile->getWarnings();

		$expected_file = $file . '.json';
		$expected = json_decode( file_get_contents( $expected_file ), true );

		$this->assertEquals(
			JSON_ERROR_NONE,
			json_last_error(),
			sprintf(
				'Expected JSON should be correctly parsed: %s',
				json_last_error_msg()
			)
		);

		$found = [];
		foreach ( $foundErrors as $line => $columns ) {
			foreach ( $columns as $column => $errors ) {
				foreach ( $errors as $error ) {
					$found[ $line ][] = [
						'source' => $error['source'],
						'type'   => 'error',
					];
				}
			}
		}
		foreach ( $foundWarnings as $line => $columns ) {
			foreach ( $columns as $column => $errors ) {
				foreach ( $errors as $error ) {
					$found[ $line ][] = [
						'source' => $error['source'],
						'type'   => 'warning',
					];
				}
			}
		}

		$this->assertEquals( $expected, $found, sprintf( 'File %s should only contain specified errors', $rel_file ) );
		// var_dump( $foundErrors );
	}
}
