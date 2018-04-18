<?php

use Foo\Bar;
use Foo\Baz as Zztop;

require( 'some/file/that/exists.php' );

$foo = 1;

$a = function () use ( $foo ) {
	return new Bar( $foo + 1 );
};

$b = function ( $i, $j ) use ( $foo ) {
	return $foo + 1;
};

$c = function ( $i, $j = null ) use ( $foo ) {
	return $foo + 1;
};

$c = function ( ...$i ) use ( $foo ) {
	return $foo + 1;
};

$c = function () use ( $foo ) : int {
	return $foo + 1;
};

$c = function ( ...$i ) use ( $foo ) {
	return $foo + 1;
};

$c = function ( ...$i ) use ( $foo ) : int {
	return $foo + 1;
};

add_action( 'init', function () use ( $foo ) {
	return new Zztop( $foo + 1 );
}, 1000 );

$c = function() {
	return true;
};
