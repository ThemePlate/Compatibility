<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use ThemePlate\Compatibility\Requirements\MinimumVersion;
use PHPUnit\Framework\TestCase;

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
	}
}
