<?php

$foo = [];

// Non-allowed server keys are unsafe to use without checks.
$foo[] = $_SERVER['HTTP_X_FOO'];
