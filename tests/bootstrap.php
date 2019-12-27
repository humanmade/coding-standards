<?php

// Setup some constants that PHPCS uses for testing.
define( 'PHP_CODESNIFFER_IN_TESTS', true );
define( 'PHP_CODESNIFFER_CBF', false );

// Check phpcs is installed.
$phpcs_dir = dirname( __DIR__ ) . '/vendor/squizlabs/php_codesniffer';
if ( ! file_exists($phpcs_dir)) {
	throw new Exception( 'Could not find PHP_CodeSniffer. Run `composer install --prefer-source --dev`' );
}

// Check phpcs' test framework is available.
$test_file = $phpcs_dir . '/tests/Standards/AllSniffs.php';
if ( ! file_exists( $test_file ) ) {
	throw new Exception( "Could not find PHP_CodeSniffer's tests. Make sure you run composer with `--prefer-source`" );
}

// Require autoloader and bootstrap.
require dirname( __DIR__ ) . '/vendor/autoload.php';
require $phpcs_dir . '/tests/bootstrap.php';
