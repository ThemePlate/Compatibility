<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use ThemePlate\Compatibility\Requirements\MinimumVersion;
use PHPUnit\Framework\TestCase;
use ThemePlate\Compatibility\Requirements\PHPVersion;
use ThemePlate\Compatibility\Requirements\WPVersion;

class MinimumVersionTest extends TestCase {
	public function for_possibilities(): array {
		return array(
			array( '0.1.0', '0.1.0', true ),
			array( '0.3.0', '0.2.0', false ),
			array( '4.5.6', '', false ),
			array( '', '4.5.6', true ),
		);
	}

	/** @dataProvider for_possibilities */
	public function test_possibilities( string $wanted, string $have, bool $pass ): void {
		$class = new class( $wanted, $have ) extends MinimumVersion {
			protected string $have;

			public function __construct( $wanted, $have ) {
				parent::__construct( $wanted );

				$this->have = $have;
			}

			public function installed(): string {
				return $this->have;
			}
		};

		if ( $pass ) {
			$this->assertTrue( $class->satisfied() );
		} else {
			$this->assertFalse( $class->satisfied() );
		}

		$this->assertSame( "The `$wanted` minimum version is not met", $class->message() );
	}

	public function for_message(): array {
		global $wp_version;

		$wp_version = '6.1.1'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		return array(
			array( new PHPVersion( '8.0' ), 'Requires at least PHP version %1$s (Installed v%2$s)', 'Requires at least PHP version 8.0 (Installed v' . PHP_VERSION . ')' ),
			array( new PHPVersion( '8.2' ), 'Requires PHP %1$s or higher but currently running at %2$s', 'Requires PHP 8.2 or higher but currently running at ' . PHP_VERSION ),
			array( new WPVersion( '6.2' ), 'Requires at least WP version %1$s (Installed v%2$s)', 'Requires at least WP version 6.2 (Installed v' . $wp_version . ')' ),
			array( new WPVersion( '7.0' ), 'Requires WP %1$s or higher but currently running at %2$s', 'Requires WP 7.0 or higher but currently running at ' . $wp_version ),
		);
	}

	/** @dataProvider for_message */
	public function test_message( MinimumVersion $class, string $format, string $expected ): void {
		$this->assertSame( $expected, $class->message( $format ) );
	}
}
