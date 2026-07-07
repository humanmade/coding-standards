<?php

namespace HM\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Test class for RegisterBlockTypePath sniff.
 *
 * @group hm-sniffs
 */
class RegisterBlockTypePathUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [
			15 => 1,
			16 => 1,
			17 => 1,
			20 => 1,
			21 => 1,
			22 => 1,
			25 => 1,
			26 => 1,
			29 => 1,
			30 => 1,
			31 => 1,
			32 => 1,
			35 => 1,
			38 => 1,
		];
	}

}
