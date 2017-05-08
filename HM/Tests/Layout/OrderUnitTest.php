<?php

namespace HM\Tests\Layout;

use AbstractSniffUnitTest;

class OrderUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			3 => 1,
			5 => 1,
			7 => 1,
			8 => 1,
			12 => 1,
			23 => 1,
			24 => 1,
		);
	}
	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array();
	}

}
