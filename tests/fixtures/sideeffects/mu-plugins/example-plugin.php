<?php
/**
 * Plugin Name: Example single-file mu-plugin.
 *
 * Single-file mu-plugins routinely declare symbols and add hooks in the same
 * file, so PSR1.Files.SideEffects should not flag them.
 */

namespace HM\Coding\Standards\Example;

add_action( 'init', __NAMESPACE__ . '\\bootstrap' );

/**
 * Bootstrap the plugin.
 */
function bootstrap() {
	// ...
}
