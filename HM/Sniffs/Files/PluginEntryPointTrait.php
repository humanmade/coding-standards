<?php
namespace HM\Sniffs\Files;

use PHP_CodeSniffer\Files\File;

/**
 * Shared helper for detecting plugin entry points.
 *
 * We support two patterns:
 *
 * "Single-file mu-plugins" live as a direct child of a `mu-plugins/` directory
 * (or `client-mu-plugins/`, on VIP) and encapsulate a complete plugin within
 * one single PHP file.
 *
 * A plugin entrypoint may alternatively live nested within a plugin folder,
 * conventionally named after its parent folder or else called plugin.php.
 * These ordinary plugin entrypoints can live within `plugins/`, `mu-plugins/`
 * or `client-mu-plugins/` alike.
 *
 * Both are useful patterns to support, and should be exempt from certain rules:
 * entrypoint files can't be split into an inc/ directory and collide or lose
 * meaning if renamed to namespace.php.
 */
trait PluginEntryPointTrait {
	/**
	 * Is the file being checked a plugin entry point?
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @return bool True if the file is a single-file mu-plugin or a nested plugin entry point.
	 */
	protected function is_plugin_entry_point( File $phpcsFile ) {
		$path = $phpcsFile->getFilename();

		// Normalize the directory separator across operating systems.
		if ( DIRECTORY_SEPARATOR !== '/' ) {
			$path = str_replace( DIRECTORY_SEPARATOR, '/', $path );
		}

		// Naming a file plugin.php conventionally signals it's a plugin entrypoint.
		if ( basename( $path ) === 'plugin.php' ) {
			return true;
		}

		// Single-file mu-plugin: a direct child of (client-)mu-plugins/.
		if ( preg_match( '#/(?:client-)?mu-plugins/[^/]+\.php$#', $path ) ) {
			return true;
		}

		// Nested entry point: the conventionally named main file directly
		// inside a plugin's own folder, under plugins/, mu-plugins/ or
		// client-mu-plugins/. Anything deeper is ordinary plugin code.
		if ( ! preg_match( '#/(?:(?:client-)?mu-)?plugins/([^/]+)/([^/]+)\.php$#', $path, $matches ) ) {
			return false;
		}

		// Check for conventional pattern <plugin-name>/<plugin-name>.php.
		[ , $dir, $file ] = $matches;
		return $file === $dir;
	}
}
