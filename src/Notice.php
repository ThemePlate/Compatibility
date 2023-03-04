<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility;

use ThemePlate\Compatibility\Requirements\DefinedConstant;
use WP_CLI;
use WP_Error;

class Notice {

	protected string $header;
	protected WP_Error $error;


	public function __construct( string $header, WP_Error $error ) {

		$this->header = $header;
		$this->error  = $error;

	}


	public function print(): void {

		if ( ( new DefinedConstant( 'WP_CLI' ) )->satisfied() ) {
			$this->print_cli();
		} else {
			add_action( 'admin_notices', array( $this, 'print_web' ) );
		}

	}


	public function print_cli(): void {

		WP_CLI::warning( $this->header );

		foreach ( $this->error->get_error_messages() as $error ) {
			WP_CLI::line( wp_strip_all_tags( $error ) );
		}

	}


	public function print_web(): void {

		?>
		<div class="notice notice-warning">
			<h2><?php echo esc_html( $this->header ); ?></h2>
			<ul>
				<?php
				foreach ( $this->error->get_error_messages() as $error ) {
					printf( '<li>%s</li>', wp_kses_post( $error ) );
				}
				?>
			</ul>
		</div>
		<?php

	}

}
