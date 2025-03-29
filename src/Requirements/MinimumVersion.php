<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility\Requirements;

use ThemePlate\Compatibility\BaseRequirement;

abstract class MinimumVersion extends BaseRequirement {

	public const DEFAULT_MESSAGE_FORMAT = 'The `%s` minimum version is not met';


	public function satisfied(): bool {

		return version_compare( $this->installed(), $this->requisite, '>=' );

	}


	/**
	 * @return string The version number installed
	 */
	abstract public function installed(): string;


	public function message( ?string $format = null ): string {

		if ( null === $format || '' === $format ) {
			$format = static::DEFAULT_MESSAGE_FORMAT;
		}

		return sprintf( $format, $this->requisite, $this->installed() );

	}

}
