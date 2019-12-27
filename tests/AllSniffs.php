<?php
/**
 * A test class for testing all sniffs for installed standards.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace HM\CodingStandards\Tests;

use PHP_CodeSniffer\Util\Standards;
use PHP_CodeSniffer\Autoload;
use PHPUnit\TextUI\TestRunner;
use PHPUnit\Framework\TestSuite;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class AllSniffs
 */
class AllSniffs {
	const TEST_SUFFIX = 'UnitTest.php';

	/**
	 * Prepare the test runner.
	 *
	 * @return void
	 */
	public static function main() {
		TestRunner::run( self::suite() );
	}

	/**
	 * Add all sniff unit tests into a test suite.
	 *
	 * Sniff unit tests are found by recursing through the 'Tests' directory
	 * of each installed coding standard.
	 *
	 * @return \PHPUnit\Framework\TestSuite
	 */
	public static function suite() {
		$GLOBALS['PHP_CODESNIFFER_SNIFF_CODES']   = array();
		$GLOBALS['PHP_CODESNIFFER_FIXABLE_CODES'] = array();

		$suite = new TestSuite( 'HM Standards' );

		$standards_dir = dirname( __DIR__ ) . '/HM';
		$all_details = Standards::getInstalledStandardDetails( false, $standards_dir );
		$details = $all_details['HM'];

		Autoload::addSearchPath( $details['path'], $details['namespace'] );

		$test_dir = $details['path'] . '/Tests/';
		if ( is_dir( $test_dir ) === false ) {
			// No tests for this standard.
			return $suite;
		}

		$di = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $test_dir ) );

		foreach ( $di as $file ) {
			$filename = $file->getFilename();

			// Skip hidden files.
			if ( substr( $filename, 0, 1 ) === '.' ) {
				continue;
			}

			// Tests must end with "UnitTest.php"
			if ( substr( $filename, -1 * strlen( static::TEST_SUFFIX ) ) !== static::TEST_SUFFIX ) {
				continue;
			}

			$className = Autoload::loadFile( $file->getPathname() );
			$GLOBALS['PHP_CODESNIFFER_STANDARD_DIRS'][ $className ] = $details['path'];
			$GLOBALS['PHP_CODESNIFFER_TEST_DIRS'][ $className ]     = $test_dir;
			$suite->addTestSuite( $className );
		}

		return $suite;
	}
}
