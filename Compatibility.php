<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate;

use ThemePlate\Compatibility\Checker;
use ThemePlate\Compatibility\Requirements\DefinedConstant;
use ThemePlate\Compatibility\Requirements\PHPVersion;
use ThemePlate\Compatibility\Requirements\WPVersion;
use WP_CLI;

class Compatibility {

	protected string $package_name;
	protected Checker $checker;

	protected array $messages = array(
		'header' => '%s compatibility issue:',
		'wp'     => 'Requires at least WordPress version %1$s (Installed v%2$s)',
		'php'    => 'Requires at least PHP version %1$s (Installed v%2$s)',
	);


	public function __construct( string $package_name, string $wp_version, string $php_version = '7.4' ) {

		$this->package_name = $package_name;

		$this->checker = new Checker();

		$this->checker->add( 'wp', new WPVersion( $wp_version ) );
		$this->checker->add( 'php', new PHPVersion( $php_version ) );

	}


	/**
	 * Set the notice header message
	 *
	 *
	 * @param string $message printf format
	 *
	 * Available directives:
	 *
	 *     1. %s - package name
	 *
	 *
	 * @return $this
	 */
	public function message_header( string $message ): self {

		$this->messages['header'] = $message;

		return $this;

	}


	/**
	 * Set the WordPress error message
	 *
	 *
	 * @param string $message sprintf format
	 *
	 * Available directives:
	 *
	 *     1. %s - required version
	 *
	 *     2. %s - installed version
	 *
	 *
	 * @return $this
	 */
	public function message_wp( string $message ): self {

		$this->messages['wp'] = $message;

		return $this;

	}


	/**
	 * Set the PHP error message
	 *
	 *
	 * @param string $message sprintf format
	 *
	 *
	 * Available directives:
	 *
	 *     1. %s - required version
	 *
	 *     2. %s - installed version
	 *
	 *
	 * @return $this
	 */
	public function message_php( string $message ): self {

		$this->messages['php'] = $message;

		return $this;

	}


	public function running_cli(): bool {

		return ( new DefinedConstant( 'WP_CLI' ) )->satisfied();

	}


	public function setup( int $priority = 10 ): void {

		$this->checker->run( $this->messages );

		if ( $this->running_cli() ) {
			$handler = $this->checker->get_error();

			if ( ! $handler->has_errors() ) {
				return;
			}

			WP_CLI::warning(
				sprintf(
					esc_html( $this->messages['header'] ),
					wp_strip_all_tags( $this->package_name )
				)
			);

			foreach ( $handler->get_error_messages() as $error ) {
				WP_CLI::line( wp_strip_all_tags( $error ) );
			}
		} else {
			add_action( 'admin_notices', array( $this, 'maybe_notice' ), $priority );
		}

	}


	public function maybe_notice(): void {

		$handler = $this->checker->get_error();

		if ( ! $handler->has_errors() ) {
			return;
		}

		?>
		<div class="notice notice-warning">
			<h2>
				<?php
				printf(
					esc_html( $this->messages['header'] ),
					wp_kses_post( $this->package_name )
				);
				?>
			</h2>
			<ul>
				<?php
				foreach ( $handler->get_error_messages() as $error ) {
					printf( '<li>%s</li>', wp_kses_post( $error ) );
				}
				?>
			</ul>
		</div>
		<?php

	}

}
