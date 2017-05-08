<?php

namespace HM\Coding\Standards\Tests;

require 'include3.php';
require_once 'include4.php';

const SOME_CONSTANT = '😎';

function code_starts_now() {

	$var = '🧀';

	$func = function() use ( $var ) {
		return $var . '😤';
	};

	return $func();
}
