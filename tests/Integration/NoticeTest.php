<?php

/**
 * @package ThemePlate
 */

namespace Tests\Integration;

use ThemePlate\Compatibility\Notice;
use ThemePlate\Tester\Utils;
use WP_Error;
use WP_UnitTestCase;

class NoticeTest extends WP_UnitTestCase {
	public function for_type(): array {
		return array(
			array( 'success', true ),
			array( 'random', false ),
		);
	}

	/** @dataProvider for_type */
	public function test_type( string $type, bool $valid ) {
		$value = Utils::get_inaccessible_property( new Notice( $type ), 'type' );

		if ( $valid ) {
			$this->assertSame( $type, $value );
		} else {
			$this->assertSame( Notice::LEVELS[0], $value );
		}
	}

	public function test_cli(): void {
		$messages = array(
			'First Error',
			'Second Error',
		);

		$error = new WP_Error();

		foreach ( $messages as $index => $message ) {
			$error->add( $index, $message );
		}

		$messages[] = ''; // Expected new line

		ob_start();
		( new Notice( 'info' ) )->set_error( $error )->print_cli();
		// Intentional no printed header
		$this->assertSame( implode( "\n", $messages ), ob_get_clean() );
	}

	public function test_web(): void {
		$messages = array(
			'Initial Error',
			'Final Error',
		);

		$error = new WP_Error();

		foreach ( $messages as $index => $message ) {
			$error->add( $index, $message );
		}

		$header = 'Another test';

		ob_start();
		( new Notice( 'error' ) )->set_header( $header )->set_error( $error )->print_web();
		$actual = ob_get_clean();

		$this->assertStringContainsString( $header, $actual );

		foreach ( $messages as $message ) {
			$this->assertStringContainsString( $message, $actual );
		}
	}
}
