<?php

$var = get_option( 'foo' );

// Escaping for all the regular stuff should be fine.
echo esc_html( $var );
echo esc_attr( $var );

// Triggering errors should be fine too, since they're not sent to the browser.
trigger_error( $var );
error_log( $var );

// Deprecations and doin_it_rong too:
_deprecated_file( $var );
_doing_it_wrong( $var );

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
