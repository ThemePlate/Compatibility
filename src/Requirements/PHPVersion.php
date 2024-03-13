<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility\Requirements;

class PHPVersion extends MinimumVersion {

	public const DEFAULT_MESSAGE_FORMAT = 'PHP v%1$s requirement is not met (Installed v%2$s)';


	public function identifier(): string {

		return $this->identifier;

	}


	/**
	 * @return string The version number installed
	 */
	public function installed(): string {

		return PHP_VERSION;

	}

}
