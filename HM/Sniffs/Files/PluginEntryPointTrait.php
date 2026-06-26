<?php
namespace HM\Sniffs\Files;

use PHP_CodeSniffer\Files\File;

/**
 * Shared helper for detecting plugin entry points.
 *
 * Two shapes qualify. A single-file mu-plugin lives as a direct child of a
 * `mu-plugins/` directory (or `client-mu-plugins/`, on VIP); this is specific
 * to mu-plugins, whose flat auto-loading means a plugin physically can't be
 * split into a folder. A nested plugin's entry point is the conventionally
 * named main file directly inside its own folder -- `<plugin>/<plugin>.php` or
 * `<plugin>/plugin.php` -- under `plugins/`, `mu-plugins/` or
 * `client-mu-plugins/`.
 *
 * Both are useful patterns to support: they can't be split into an inc/
 * directory or a named namespace.php, so they're exempt from those rules.
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

		// Conventional entry-point names: <dir>/<dir>.php or <dir>/plugin.php.
		[ , $dir, $file ] = $matches;
		return $file === 'plugin' || $file === $dir;
	}
}
