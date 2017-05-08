<?php

namespace HM\Coding\Standards\Tests;

require 'include1.php';
require_once 'include2.php';

const SOME_CONSTANT = '😎';

function code_starts_now() {

	$var = '🧀';

	$func = function() use ( $var ) {
		return $var . '😤';
	};

	return $func();
}

include 'include3.php';
include_once 'include4.php';
