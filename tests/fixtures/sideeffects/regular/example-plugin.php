<?php
/**
 * Plugin Name: Example regular plugin file.
 *
 * Identical to the mu-plugin fixture, but not a direct child of mu-plugins/,
 * so PSR1.Files.SideEffects should still flag it.
 */

namespace HM\Coding\Standards\Example;

add_action( 'init', __NAMESPACE__ . '\\bootstrap' );

/**
 * Bootstrap the plugin.
 */
function bootstrap() {
	// ...
}
