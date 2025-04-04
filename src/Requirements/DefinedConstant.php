<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility\Requirements;

use ThemePlate\Compatibility\BaseRequirement;

class DefinedConstant extends BaseRequirement {

	public const DEFAULT_MESSAGE_FORMAT = 'The constant `%s` is not defined';


	public function satisfied(): bool {

		return defined( $this->requisite );

	}

}
