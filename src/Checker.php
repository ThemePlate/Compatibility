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


	public function __construct( ?WP_Error $error = null ) {

		$this->error = $error ?? new WP_Error();

	}


	public function add( RequirementInterface $requirement ): Checker {

		$this->requirements[ $requirement->identifier() ] = $requirement;

		return $this;

	}


	public function run( array $messages = array() ): void {

		foreach ( $this->requirements as $requirement ) {
			if ( ! $requirement->satisfied() ) {
				$code = $requirement->identifier();

				$this->error->add( $code, $requirement->message( $messages[ $code ] ?? '' ) );
			}
		}

	}


	public function get_error(): WP_Error {

		return $this->error;

	}

}
