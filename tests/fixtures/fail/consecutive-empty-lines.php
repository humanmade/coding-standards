<?php
/**
 * Consecutive empty lines should generate an error.
 */


function foo() {
	$variable = 'something';


	return $variable;
}

