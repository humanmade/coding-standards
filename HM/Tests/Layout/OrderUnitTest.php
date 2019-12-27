<?php

namespace HM\Tests\Layout;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class OrderUnitTest
 *
 * @group hm-sniffs
 */
class OrderUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			1  => 1,
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
