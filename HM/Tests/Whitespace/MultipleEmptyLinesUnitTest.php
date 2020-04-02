<?php

namespace HM\Tests\Whitespace;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class MultipleEmptyLinesUnitTest
 *
 * @group hm-sniffs
 */
class MultipleEmptyLinesUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		$file = func_get_arg( 0 );
		switch ( $file ) {
			case 'MultipleEmptyLinesUnitTest.success':
				return [];

			case 'MultipleEmptyLinesUnitTest.fail':
				return [
					4 => 1,
					11 => 1,
					17 => 1,
					22 => 1,
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
