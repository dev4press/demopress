<?php

namespace Dev4Press\Plugin\DEMOPRESS\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Builder {
	public $name = '';

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

	public function settings( $base, $type, $name, $class ) {
		return array();
	}

	abstract public function run( $settings = array() );
}
