<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility;

use ReflectionClass;

abstract class BaseRequirement implements RequirementInterface {

	protected string $requisite;
	protected string $identifier;


	public function __construct( string $requisite ) {

		$this->identifier = ( new ReflectionClass( $this ) )->getShortName();

		$this->requisite = $requisite;

	}


	public function identifier(): string {

		return $this->identifier . ':' . $this->requisite;

	}


	public function requisite(): string {

		return $this->requisite;

	}


	public function message( string $format ): string {

		return sprintf( $format, $this->requisite );

	}

}
