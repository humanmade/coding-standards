<?php

namespace HM\Tests\Files;

use DirectoryIterator;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class FunctionFileNameUnitTest
 *
 * @group hm-sniffs
 */
class FunctionFileNameUnitTest extends AbstractSniffUnitTest {
	/**
	 * Get files to test against.
	 *
	 * Overridden from base to use the directory instead.
	 */
	protected function getTestFiles( $test_base_dir ) {
		$test_base_dir = rtrim( $test_base_dir, '.' );
		$test_files = [];

		$di = new DirectoryIterator( $test_base_dir );

		foreach ( $di as $file ) {
			if ( $file->isDot() ) {
				continue;
			}

			$test_files[] = $file->getPathname();
		}

		// Put them in order.
		sort( $test_files );

		return $test_files;
	}

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		$file = func_get_arg( 0 );
		$pass = [
			'namespace.php',
		];
		if ( in_array( $file, $pass, true ) ) {
			return [];
		}
		return [
			5 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [];
	}

}
