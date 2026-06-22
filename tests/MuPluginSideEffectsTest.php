<?php
/**
 * Test that PSR1.Files.SideEffects is excluded for single-file mu-plugins.
 *
 * The two HM file-structure sniffs handle their own mu-plugin exemption in
 * code (covered by their sniff unit tests). PSR1.Files.SideEffects is a
 * third-party sniff, so its exemption lives as an <exclude-pattern> in
 * HM/ruleset.xml.
 *
 * We exercise it by running the phpcs binary in a subprocess rather than
 * building the ruleset in-process: loading the full HM standard alongside the
 * other test suites triggers sniff class redeclaration errors, and restricting
 * the sniffs in-process makes PHPCS skip the ruleset processing that loads the
 * exclude-patterns in the first place.
 */

namespace HM\CodingStandards\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class MuPluginSideEffectsTest
 *
 * @group fixtures
 */
class MuPluginSideEffectsTest extends TestCase {
	const PSR1_SIDE_EFFECTS = 'PSR1.Files.SideEffects.FoundWithSymbols';

	/**
	 * Run PSR1.Files.SideEffects over a fixture and return the message sources.
	 *
	 * @param string $relative_file Fixture path relative to the tests directory.
	 * @return string[] List of reported message sources.
	 */
	protected function get_sources( string $relative_file ) {
		$root  = dirname( __DIR__ );
		$phpcs = $root . '/vendor/bin/phpcs';
		$file  = __DIR__ . '/' . $relative_file;

		$command = sprintf(
			'%s %s --standard=HM --sniffs=PSR1.Files.SideEffects --report=json %s',
			escapeshellarg( PHP_BINARY ),
			escapeshellarg( $phpcs ),
			escapeshellarg( $file )
		);

		$output = shell_exec( $command );
		$report = json_decode( $output, true );

		$this->assertSame( JSON_ERROR_NONE, json_last_error(), 'phpcs should return valid JSON' );

		$sources = [];
		foreach ( $report['files'] as $details ) {
			foreach ( $details['messages'] as $message ) {
				$sources[] = $message['source'];
			}
		}

		return $sources;
	}

	/**
	 * Single-file mu-plugins should be exempt from the side-effects rule.
	 */
	public function test_mu_plugin_is_exempt() {
		$sources = $this->get_sources( 'fixtures/sideeffects/mu-plugins/example-plugin.php' );
		$this->assertNotContains( static::PSR1_SIDE_EFFECTS, $sources );
	}

	/**
	 * The same code outside mu-plugins/ should still get flagged, to prove correct scoping.
	 */
	public function test_regular_file_is_flagged() {
		$sources = $this->get_sources( 'fixtures/sideeffects/regular/example-plugin.php' );
		$this->assertContains( static::PSR1_SIDE_EFFECTS, $sources );
	}
}
