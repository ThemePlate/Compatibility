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

	public const DEFAULT_MESSAGE_FORMAT = 'The class `%s` does not exists';


	public function satisfied(): bool {

		return class_exists( $this->requisite );

	}

}
