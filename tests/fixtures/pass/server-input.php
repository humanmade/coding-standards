<?php

$foo = [];

// These server keys are safe to use without further sanitising (and in some
// cases, cannot be sanitised anyway).
$foo[] = $_SERVER['HTTP_HOST'];
$foo[] = $_SERVER['HTTP_USER_AGENT'];
$foo[] = $_SERVER['HTTPS'];
$foo[] = $_SERVER['REMOTE_ADDR'];
$foo[] = $_SERVER['REQUEST_METHOD'];
$foo[] = $_SERVER['REQUEST_TIME'];
$foo[] = $_SERVER['REQUEST_TIME_FLOAT'];
$foo[] = $_SERVER['REQUEST_URI'];
$foo[] = $_SERVER['QUERY_STRING'];
$foo[] = $_SERVER['SERVER_ADDR'];
