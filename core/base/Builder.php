<?php

namespace Dev4Press\Plugin\DemoPress\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Builder {
	public $name = '';
	public $scope = '';

	public function __construct() {
	}

	/** @return Builder */
	public static function instance() {
		static $instance = array();

		$class = get_called_class();

		if ( ! isset( $instance[ $class ] ) ) {
			$instance[ $class ] = new $class();
		}

		return $instance[ $class ];
	}

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array();
	}

	protected function el_wrapper_class( $class, $hidden = false ) {
		return $class . '-switch ' . $class . '-data-' . $this->name . ( $hidden ? ' demopress-is-hidden' : '' );
	}

	protected function el_option_name( $type, $name, $option ) {
		return $type . '-builder-' . $name . '-' . $this->name . '-' . $option;
	}

	/**
	 * @param array                                      $settings
	 * @param \Dev4Press\Plugin\DemoPress\Base\Generator $generator
	 *
	 * @return mixed
	 */
	abstract public function run( $settings = array(), $generator = null );
}
