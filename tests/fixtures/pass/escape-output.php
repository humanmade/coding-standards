<?php

$var = get_option( 'foo' );

// Escaping for all the regular stuff should be fine.
echo esc_html( $var );
echo esc_attr( $var );

// Triggering errors: The message parameter must still be escaped in WPCS 3.x.
trigger_error( esc_html( $var ) );
error_log( $var );

// Deprecations and doin_it_rong too:
_deprecated_file( esc_html( $var ) );
_doing_it_wrong( esc_html( $var ) );

// Ignoring via HM or WP codes should work.
// phpcs:ignore HM.Security.EscapeOutput
echo $var;

// phpcs:ignore WordPress.Security.EscapeOutput
echo $var;

// Static strings are OK too.
echo 'Foo.';

// Automatically escaped functions are A-OK.
// Note: customisations in our standard aren't applied here, only the baked-in
// functions in wpcs.
echo do_shortcode( '[foo]' );
echo the_date();

// Custom auto-escaped functions from config (see FixtureTests.php)
echo my_custom_func();
echo another_func();

// Exception messages are internal state — escaping belongs at the output
// boundary (catch/render), not at the throw site.
throw new \RuntimeException( $var );
throw new \RuntimeException( sprintf( 'Got: %s', $var ) );
