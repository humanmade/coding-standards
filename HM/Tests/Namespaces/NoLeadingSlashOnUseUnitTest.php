<?php

namespace HM\Tests\Namespaces;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class NoLeadingSlashOnUseUnitTest
 *
 * @group hm-sniffs
 */
class NoLeadingSlashOnUseUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		$file = func_get_arg( 0 );
		switch ( $file ) {
			case 'NoLeadingSlashOnUseUnitTest.success':
				return [];

			case 'NoLeadingSlashOnUseUnitTest.fail':
				return [
					5 => 1,
					6 => 1,
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
