<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility\Requirements;

use ThemePlate\Compatibility\BaseRequirement;

class FunctionExists extends BaseRequirement {

	public function satisfied(): bool {

		return function_exists( $this->requisite );

	}

}
