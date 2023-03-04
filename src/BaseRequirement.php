<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility;

abstract class BaseRequirement implements RequirementInterface {

	protected string $requisite;


	public function __construct( string $requisite ) {

		$this->requisite = $requisite;

	}


	public function requisite(): string {

		return $this->requisite;

	}


	public function message( string $format ): string {

		return sprintf( $format, $this->requisite );

	}

}
