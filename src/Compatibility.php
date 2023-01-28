<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate;

use WP_CLI;

class Compatibility {

	protected string $package_name;
	protected string $wp_version;
	protected string $php_version;
	protected array $errors;
	protected array $messages = array(
		'header' => '%s compatibility issue.',
		'wp'     => 'Requires at least WordPress version %1$s. (Installed v%2$s)',
		'php'    => 'Requires at least PHP version %1$s. (Installed v%2$s)',
	);


	public function __construct( string $package_name, string $wp_version, string $php_version ) {

		$this->package_name = $package_name;
		$this->wp_version   = $wp_version;
		$this->php_version  = $php_version;

	}


	protected function add_error( string $message ): void {

		$this->errors[] = $message;

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
	 * @return void
	 */
	public function message_header( string $message ): void {

		$this->messages['header'] = $message;

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
	 * @return void
	 */
	public function message_wp( string $message ): void {

		$this->messages['wp'] = $message;

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
	 * @return void
	 */
	public function message_php( string $message ): void {

		$this->messages['php'] = $message;

	}


	public function valid_wp(): bool {

		if ( version_compare( $GLOBALS['wp_version'], $this->wp_version, '<' ) ) {
			$this->add_error(
				sprintf(
					$this->messages['wp'],
					$this->wp_version,
					$GLOBALS['wp_version']
				)
			);

			return false;
		}

		return true;

	}


	public function valid_php(): bool {

		if ( version_compare( PHP_VERSION, $this->php_version, '<' ) ) {
			$this->add_error(
				sprintf(
					$this->messages['php'],
					$this->php_version,
					PHP_VERSION
				)
			);

			return false;
		}

		return true;

	}


	public function running_cli(): bool {

		return defined( 'WP_CLI' ) && WP_CLI;

	}


	public function setup( int $priority = 10 ): void {

		$this->valid_wp();
		$this->valid_php();

		if ( $this->running_cli() ) {
			if ( empty( $this->errors ) ) {
				return;
			}

			WP_CLI::error(
				sprintf(
					esc_html( $this->messages['header'] ),
					wp_strip_all_tags( $this->package_name )
				),
				false
			);

			foreach ( $this->errors as $error ) {
				WP_CLI::line( wp_strip_all_tags( $error ) );
			}
		} else {
			add_action( 'admin_notices', array( $this, 'maybe_notice' ), $priority );
		}

	}


	public function maybe_notice(): void {

		if ( empty( $this->errors ) ) {
			return;
		}

		?>
		<div class="notice notice-error">
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
				foreach ( $this->errors as $error ) {
					printf( '<li>%s</li>', wp_kses_post( $error ) );
				}
				?>
			</ul>
		</div>
		<?php

	}

}
