<?php

namespace HM\Sniffs;

/**
 * Is the ignore file line a functional one?
 *
 * @return bool True for real exclusion lines, false for comments or empty lines.
 */
function is_functional_line( $line ) {
	if ( empty( $line ) ) {
		return false;
	}

	return $line[0] !== '#';
}

/**
 * Get ignore patterns from an ignore file.
 *
 * @param string $file Path to the ignore file.
 * @param string $directory Directory to treat paths as relative to.
 * @return string[] List of ignore rules.
 */
function get_ignores_from_file( $file, $directory ) {
	$content = file_get_contents( $file );
	if ( empty( $content ) ) {
		return [];
	}

	$lines = explode( "\n", $content );

	// Strip empty or comment lines.
	$lines = array_map( 'trim', $lines );
	$lines = array_filter( $lines, __NAMESPACE__ . '\\is_functional_line' );

	// Make the ignore patterns absolute.
	$lines = array_map( function ( $rule ) use ( $directory ) {
		// Strip leading ./
		if ( substr( $rule, 0, 2 ) === './' ) {
			$rule = substr( $rule, 2 );
		}

		return $directory . DIRECTORY_SEPARATOR . $rule;
	}, $lines );

	return $lines;
}

/**
 * Attach additional ignore patterns to the runner.
 *
 * @param \PHP_CodeSniffer\Runner $runner CodeSniffer runner instance.
 */
function attach_to_runner( $runner ) {
	$paths = $runner->config->files;
	$ignored = $runner->config->ignored;

	// Find exclusion files.
	$did_change = false;
	foreach ( $paths as $path ) {
		// Only use ignore files for directories.
		if ( ! is_dir( $path ) ) {
			continue;
		}

		// Find an ignore file.
		$directory = $path;
		$ignore_file = $directory . '/.phpcsignore';
		if ( ! file_exists( $ignore_file ) ) {
			continue;
		}
		if ( PHP_CODESNIFFER_VERBOSITY > 1 ) {
			echo "\tAdding exclusion rules from $ignore_file\n";
		}

		$extra_ignores = get_ignores_from_file( $ignore_file, $directory );
		if ( PHP_CODESNIFFER_VERBOSITY > 1 ) {
			foreach ( $extra_ignores as $rule ) {
				echo "\t\t=> $rule\n";
			}
		}

		$did_change = true;
		$ignored = array_merge( $ignored, $extra_ignores );
	}

	if ( $did_change ) {
		$runner->config->ignored = $ignored;
	}
}

if ( ! empty( $GLOBALS['runner'] ) ) {
	attach_to_runner( $GLOBALS['runner'] );
}
