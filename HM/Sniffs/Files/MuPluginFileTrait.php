<?php
namespace HM\Sniffs\Files;

/**
 * Shared helper for detecting single-file must-use plugins.
 *
 * Single-file mu-plugins live as direct children of a `mu-plugins/` directory
 * (or `client-mu-plugins/`, on VIP). This is a useful pattern to support, but
 * by definition can't be split into an inc/ directory or named namespace.php
 * (multiple plugins would collide), and should allow side effects.
 */
trait MuPluginFileTrait {
	/**
	 * Is the given file a single-file mu-plugin?
	 *
	 * @param string $path Full path to the file being checked.
	 * @return bool True if the file is a direct child of mu-plugins/ or client-mu-plugins/.
	 */
	protected function is_single_file_mu_plugin( $path ) {
		// Normalize the directory separator across operating systems.
		if ( DIRECTORY_SEPARATOR !== '/' ) {
			$path = str_replace( DIRECTORY_SEPARATOR, '/', $path );
		}

		return (bool) preg_match( '#/(?:client-)?mu-plugins/[^/]+\.php$#', $path );
	}
}
