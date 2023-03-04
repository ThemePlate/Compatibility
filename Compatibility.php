<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate;

use ThemePlate\Compatibility\Requirements\DefinedConstant;
use ThemePlate\Compatibility\Requirements\PHPVersion;
use ThemePlate\Compatibility\Requirements\WPVersion;
use WP_CLI;

class Compatibility {

	protected string $package_name;
	protected WPVersion $wp;
	protected PHPVersion $php;
	protected array $errors;
	protected array $messages = array(
		'header' => '%s compatibility issue:',
		'wp'     => 'Requires at least WordPress version %1$s (Installed v%2$s)',
		'php'    => 'Requires at least PHP version %1$s (Installed v%2$s)',
	);


	public function __construct( string $package_name, string $wp_version, string $php_version = '7.4' ) {

		$this->package_name = $package_name;

		$this->wp  = new WPVersion( $wp_version );
		$this->php = new PHPVersion( $php_version );

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


	public function valid_wp(): bool {

		if ( ! $this->wp->satisfied() ) {
			$this->add_error(
				sprintf(
					$this->messages['wp'],
					$this->wp->requisite(),
					$this->wp->installed()
				)
			);

			return false;
		}

		return true;

	}


	public function valid_php(): bool {

		if ( ! $this->php->satisfied() ) {
			$this->add_error(
				sprintf(
					$this->messages['php'],
					$this->php->requisite(),
					$this->php->installed()
				)
			);

			return false;
		}

		return true;

	}


	public function running_cli(): bool {

		return ( new DefinedConstant( 'WP_CLI' ) )->satisfied();

	}


	public function setup( int $priority = 10 ): void {

		$this->valid_wp();
		$this->valid_php();

		if ( $this->running_cli() ) {
			if ( empty( $this->errors ) ) {
				return;
			}

			WP_CLI::warning(
				sprintf(
					esc_html( $this->messages['header'] ),
					wp_strip_all_tags( $this->package_name )
				)
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
				foreach ( $this->errors as $error ) {
					printf( '<li>%s</li>', wp_kses_post( $error ) );
				}
				?>
			</ul>
		</div>
		<?php

	}

}
