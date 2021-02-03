<?php

// Short ternaries.
$result = $expr ?: true;
$result = $expr ?: false;
$result = $expr ? : true;
$result = $expr ? : false;

// Only one Boolean values.
$result = $expr ? true : null;
$result = $expr ? '' : false;

// Nested ternaries. We don't want to do this, but the sniff should not flag this, at least.
$result = $expr ? true : $other ? false : null;
