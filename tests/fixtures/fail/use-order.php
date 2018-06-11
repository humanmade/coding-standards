<?php

use Foo\Bar;

require( 'some/file/that/exists.php' );

$foo = 1;

$a = function () use ( $foo ) {
	return new Bar( $foo + 1 );
};

use Foo\Baz as Zztop;

$b = function () use ( $foo ) {
	return new Zztop( $foo + 1 );
};
