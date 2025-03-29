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

	protected string $type;
	protected bool $dismissible;
	protected ?string $header  = null;
	protected ?WP_Error $error = null;

	public const LEVELS = array(
		'warning',
		'info',
		'error',
		'success',
	);


	public function __construct( string $type, bool $dismissible = false ) {

		if ( ! in_array( $type, self::LEVELS, true ) ) {
			$type = self::LEVELS[0];
		}

		$this->type = $type;

		$this->dismissible = $dismissible;

	}


	public function set_error( WP_Error $error ): Notice {

		$this->error = $error;

		return $this;

	}


	public function set_header( string $header ): Notice {

		$this->header = $header;

		return $this;

	}


	public function print(): void {

		if ( ( new DefinedConstant( 'WP_CLI' ) )->satisfied() ) {
			$this->print_cli();
		} else {
			add_action( 'admin_notices', array( $this, 'print_web' ) );
		}

	}


	public function print_cli(): void {

		if ( null !== $this->header ) {
			WP_CLI::warning( $this->header );
		}

		if ( ! $this->error instanceof WP_Error ) {
			return;
		}

		foreach ( $this->error->get_error_messages() as $error ) {
			WP_CLI::line( wp_strip_all_tags( $error ) );
		}

	}


	public function print_web(): void {

		if ( null === $this->header && ! $this->error instanceof WP_Error ) {
			return;
		}

		?>
		<div class="notice notice-<?php echo esc_attr( $this->type ); ?><?php echo $this->dismissible ? ' is-dismissible' : ''; ?>">
			<?php if ( null !== $this->header ) : ?>
			<p><?php echo wp_kses_post( $this->header ); ?></p>
			<?php endif; ?>

			<?php if ( $this->error instanceof WP_Error ) : ?>
			<ul>
				<?php
				foreach ( $this->error->get_error_messages() as $error ) {
					printf( '<li>%s</li>', wp_kses_post( $error ) );
				}
				?>
			</ul>
			<?php endif; ?>
		</div>
		<?php

	}

}
