<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility\Requirements;

use ThemePlate\Compatibility\BaseRequirement;

class ClassExists extends BaseRequirement {

	public function satisfied(): bool {

		return class_exists( $this->requisite );

	}

}
