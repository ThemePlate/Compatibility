<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility\Requirements;

class PHPVersion extends MinimumVersion {

	/**
	 * @return string The version number installed
	 */
	public function installed(): string {

		return PHP_VERSION;

	}

}
