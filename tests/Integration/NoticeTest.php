<?php

/**
 * @package ThemePlate
 */

namespace Tests\Integration;

use ThemePlate\Compatibility\Notice;
use WP_Error;
use WP_UnitTestCase;

class NoticeTest extends WP_UnitTestCase {
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
		( new Notice( 'This is a test' ) )->set_error( $error )->print_cli();
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
		( new Notice( $header ) )->set_error( $error )->print_web();
		$actual = ob_get_clean();

		$this->assertStringContainsString( $header, $actual );

		foreach ( $messages as $message ) {
			$this->assertStringContainsString( $message, $actual );
		}
	}
}
