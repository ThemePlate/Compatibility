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
		$this->assertFalse( ( new DefinedConstant( 'TEST_EMPTY' ) )->satisfied() );

		define( 'TEST_NULL', null );
		$this->assertFalse( ( new DefinedConstant( 'TEST_NULL' ) )->satisfied() );

		define( 'TEST_FALSE', false );
		$this->assertFalse( ( new DefinedConstant( 'TEST_FALSE' ) )->satisfied() );

		define( 'TEST_TRUE', true );
		$this->assertTrue( ( new DefinedConstant( 'TEST_TRUE' ) )->satisfied() );

		define( 'TEST_INT', 123 );
		$this->assertTrue( ( new DefinedConstant( 'TEST_INT' ) )->satisfied() );

		define( 'TEST_STRING', 'test' );
		$this->assertTrue( ( new DefinedConstant( 'TEST_STRING' ) )->satisfied() );

		define( 'TEST_ARRAY', array( 'test' ) );
		$this->assertTrue( ( new DefinedConstant( 'TEST_ARRAY' ) )->satisfied() );
	}
}
