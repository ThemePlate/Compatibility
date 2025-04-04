<?php

/**
 * Simple compatibility helper class
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate;

use ThemePlate\Compatibility\Checker;
use ThemePlate\Compatibility\Notice;
use ThemePlate\Compatibility\Requirements\PHPVersion;
use ThemePlate\Compatibility\Requirements\WPVersion;

class Compatibility {

	protected Checker $checker;

	protected array $messages = array(
		'header'     => '%s compatibility issue:',
		'WPVersion'  => 'Requires at least WordPress version %1$s (Installed v%2$s)',
		'PHPVersion' => 'Requires at least PHP version %1$s (Installed v%2$s)',
	);


	public function __construct( string $wp_version, string $php_version = '7.4' ) {

		$this->checker = new Checker();

		$this->checker->add( new WPVersion( $wp_version ) );
		$this->checker->add( new PHPVersion( $php_version ) );

	}


	/**
	 * Set the notice header message
	 *
	 *
	 * @param string $message printf format
	 *
	 * Available directives:
	 *
	 *     1. %s - package name
	 *
	 *
	 * @return $this
	 */
	public function message_header( string $message ): self {

		$this->messages['header'] = $message;

		return $this;

	}


	/**
	 * Set the WordPress error message
	 *
	 *
	 * @param string $message sprintf format
	 *
	 * Available directives:
	 *
	 *     1. %s - required version
	 *
	 *     2. %s - installed version
	 *
	 *
	 * @return $this
	 */
	public function message_wp( string $message ): self {

		$this->messages['WPVersion'] = $message;

		return $this;

	}


	/**
	 * Set the PHP error message
	 *
	 *
	 * @param string $message sprintf format
	 *
	 *
	 * Available directives:
	 *
	 *     1. %s - required version
	 *
	 *     2. %s - installed version
	 *
	 *
	 * @return $this
	 */
	public function message_php( string $message ): self {

		$this->messages['PHPVersion'] = $message;

		return $this;

	}


	public function setup( string $package_name ): void {

		$this->checker->run( $this->messages );

		$handler = $this->checker->get_error();

		if ( ! $handler->has_errors() ) {
			return;
		}

		( new Notice( 'warning' ) )->set_header(
			sprintf(
				$this->messages['header'],
				'<strong>' . $package_name . '</strong>'
			)
		)->set_error( $handler )->print();

	}

}
