<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility\Requirements;

use ThemePlate\Compatibility\BaseRequirement;

class WritablePath extends BaseRequirement {

	public function identifier(): string {

		return $this->requisite;

	}


	public function satisfied(): bool {

		return ( is_dir( $this->requisite ) || is_file( $this->requisite ) ) && wp_is_writable( $this->requisite );

	}

}
