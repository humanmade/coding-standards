<?php

namespace HM\Tests\Classes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class OnlyClassInFileUnitTest
 *
 * @group hm-sniffs
 */
class OnlyClassInFileUnitTest extends AbstractSniffUnitTest {

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
		$file = func_get_arg( 0 );
		list( $_, $type, $variant ) = explode( '.', $file, 3 );
		if ( $type !== 'fail' ) {
			return [];
		}

		return [
			1 => 1,
		];
	}

}
