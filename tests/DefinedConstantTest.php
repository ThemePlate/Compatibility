<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use ThemePlate\Compatibility\Requirements\DefinedConstant;
use PHPUnit\Framework\TestCase;

class DefinedConstantTest extends TestCase {
	public function test_possibilities(): void {
		$this->assertFalse( ( new DefinedConstant( 'test' ) )->satisfied() );

		define( 'TEST_EMPTY', '' );
		$this->assertTrue( ( new DefinedConstant( 'TEST_EMPTY' ) )->satisfied() );
		$this->assertFalse( (bool) constant( 'TEST_EMPTY' ) );

		define( 'TEST_NULL', null );
		$this->assertTrue( ( new DefinedConstant( 'TEST_NULL' ) )->satisfied() );
		$this->assertFalse( (bool) constant( 'TEST_NULL' ) );

		define( 'TEST_FALSE', false );
		$this->assertTrue( ( new DefinedConstant( 'TEST_FALSE' ) )->satisfied() );
		$this->assertFalse( (bool) constant( 'TEST_FALSE' ) );

		define( 'TEST_TRUE', true );
		$this->assertTrue( ( new DefinedConstant( 'TEST_TRUE' ) )->satisfied() );

		define( 'TEST_INT', 123 );
		$this->assertTrue( ( new DefinedConstant( 'TEST_INT' ) )->satisfied() );

		define( 'TEST_STRING', 'test' );
		$this->assertTrue( ( new DefinedConstant( 'TEST_STRING' ) )->satisfied() );

		define( 'TEST_ARRAY', array( 'test' ) );
		$this->assertTrue( ( new DefinedConstant( 'TEST_ARRAY' ) )->satisfied() );
	}

	public function for_message(): array {

		return array(
			array( 'Requires the constant `%1$s`', 'Requires the constant `TESTER`' ),
			array( 'Constant "%1$s" is required', 'Constant "TESTER" is required' ),
		);
	}

	/** @dataProvider for_message */
	public function test_message( string $format, string $expected ): void {
		$this->assertSame( $expected, ( new DefinedConstant( 'TESTER' ) )->message( $format ) );
	}
}
