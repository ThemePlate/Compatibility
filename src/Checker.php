<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Compatibility;

use WP_Error;

class Checker {

	protected WP_Error $error;

	/** @var RequirementInterface[] */
	protected array $requirements;


	public function __construct( WP_Error $error = null ) {

		$this->error = $error ?? new WP_Error();

	}


	public function add( string $identifier, RequirementInterface $requirement ): Checker {

		$this->requirements[ $identifier ] = $requirement;

		return $this;

	}


	public function run( array $messages ): void {

		foreach ( $this->requirements as $identifier => $requirement ) {
			if ( ! $requirement->satisfied() ) {
				$this->error->add( $identifier, $requirement->message( $messages[ $identifier ] ?? '' ) );
			}
		}

	}


	public function get_error(): WP_Error {

		return $this->error;

	}

}
