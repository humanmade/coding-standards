<?php

namespace HM\Tests\Files;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class PatternSlugMatchesFilenameUnitTest
 *
 * @group hm-sniffs
 */
class PatternSlugMatchesFilenameUnitTest extends AbstractSniffUnitTest {
	/**
	 * Get files to test against.
	 *
	 * Overridden from base to recurse through the fixture directory, since
	 * the sniff only applies to files within a theme's patterns directory.
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
		$fail = [
			'renamed-pattern.php' => [
				4 => 1,
			],
		];

		return $fail[ $file ] ?? [];
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
