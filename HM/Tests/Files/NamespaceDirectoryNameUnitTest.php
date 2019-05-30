<?php

namespace HM\Tests\Files;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class NamespaceDirectoryNameUnitTest
 *
 * @group hm-sniffs
 */
class NamespaceDirectoryNameUnitTest extends AbstractSniffUnitTest {
	/**
	 * Get files to test against.
	 *
	 * Overridden from base to use the directory instead.
	 */
	protected function getTestFiles( $test_base_dir ) {
		$test_base_dir = rtrim( $test_base_dir, '.' );
		$test_files = [];

		$di = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $test_base_dir )
		);

		foreach ( $di as $file ) {
			if ( ! $file->isFile() ) {
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
		switch ( $file ) {
			case 'pass.php':
				return [];

			default:
				return [
					3 => 1,
				];
		}
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
