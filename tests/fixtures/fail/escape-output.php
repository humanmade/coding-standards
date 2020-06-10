<?php

$var = get_option( 'foo' );

// Simple printing.
echo $var;
echo( $var );
print $var;
print( $var );

// Slightly more complex.
printf( '%s', $var );
vprintf( '%s', $var );
exit( $var );
die( $var );
wp_die( $var );

// Via translations.
_e( $var );
_ex( $var );
