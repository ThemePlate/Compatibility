<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility;

interface RequirementInterface {

	public function requisite(): string;

	public function satisfied(): bool;

}
