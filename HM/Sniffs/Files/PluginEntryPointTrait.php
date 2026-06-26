<?php
namespace HM\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Shared helper for detecting plugin entry points.
 *
 * Two shapes qualify. A single-file mu-plugin lives as a direct child of a
 * `mu-plugins/` directory (or `client-mu-plugins/`, on VIP); this is specific
 * to mu-plugins, whose flat auto-loading means a plugin physically can't be
 * split into a folder. A nested plugin's entry point is the file directly
 * inside its own folder under `plugins/` (or `mu-plugins/`/`client-mu-plugins/`)
 * carrying the plugin header; by convention it is `<plugin>/<plugin>.php` or
 * `<plugin>/plugin.php`, but any such file declaring a Plugin Name header counts.
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

		// Nested entry point: a file directly inside a plugin's own folder,
		// under plugins/, mu-plugins/ or client-mu-plugins/. Anything deeper
		// is ordinary plugin code.
		if ( ! preg_match( '#/(?:(?:client-)?mu-)?plugins/([^/]+)/([^/]+)\.php$#', $path, $matches ) ) {
			return false;
		}

		// Conventional entry-point names: <dir>/<dir>.php or <dir>/plugin.php.
		[ , $dir, $file ] = $matches;
		if ( $file === 'plugin' || $file === $dir ) {
			return true;
		}

		// Otherwise fall back to the authoritative test: a Plugin Name header.
		return $this->has_plugin_header( $phpcsFile );
	}

	/**
	 * Does the file open with a Plugin Name header comment?
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @return bool True if a Plugin Name header precedes the first line of code.
	 */
	protected function has_plugin_header( File $phpcsFile ) {
		$tokens = $phpcsFile->getTokens();

		foreach ( $tokens as $token ) {
			// Allow the opening tag and whitespace before the header.
			if ( $token['code'] === T_OPEN_TAG || $token['code'] === T_WHITESPACE ) {
				continue;
			}

			// Scan the leading comment block(s) for the header field.
			if ( in_array( $token['code'], Tokens::$commentTokens, true ) ) {
				if ( stripos( $token['content'], 'Plugin Name:' ) !== false ) {
					return true;
				}
				continue;
			}

			// First real code reached; the header (if any) is before it.
			break;
		}

		return false;
	}
}
