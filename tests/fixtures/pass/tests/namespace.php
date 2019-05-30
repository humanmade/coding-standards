<?php

namespace HM\Coding\Standards\Tests;

use WP_Post;

/**
 * Test function.
 */
function run_test( $tester ) {
	$foo = get_foo();

	foreach ( $foo as $x => &$y ) {
		if ( ! $y ) {
			continue;
		}

		echo $y;
	}

	return $foo;
}

/**
 * Anonymous functions with `use` should not trigger an order warning.
 */
function anonymous_function() {
	$x = 0;

	return function () use ( $x ) {
		$x++;
		return new WP_Post( $x );
	};
}
