<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate;

class Compatibility {

	protected string $package_name;
	protected string $wp_version;
	protected string $php_version;
	protected array $errors;


	public function __construct( string $package_name, string $wp_version, string $php_version ) {

		$this->package_name = $package_name;
		$this->wp_version   = $wp_version;
		$this->php_version  = $php_version;

	}


	protected function add_error( string $message ): void {

		$this->errors[] = $message;

	}


	public function valid_wp(): bool {

		if ( version_compare( $GLOBALS['wp_version'], $this->wp_version, '<' ) ) {
			$this->add_error(
				sprintf(
					'Requires at least WordPress version %s',
					'<strong>' . $this->wp_version . '</strong>'
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
					'Requires at least PHP version %s',
					'<strong>' . $this->php_version . '</strong>'
				)
			);

			return false;
		}

		return true;

	}


	public function maybe_notice(): void {

		$this->valid_wp();
		$this->valid_php();

		if ( empty( $this->errors ) ) {
			return;
		}

		?>
		<div class="notice notice-error">
			<h2>
				<?php printf(
					'%s compatibility issue.',
					$this->package_name
				); ?>
			</h2>
			<ul>
				<?php foreach ( $this->errors as $error ) {
					printf( '<li>%s</li>', $error );
				} ?>
			</ul>
		</div>
		<?php

	}

}
