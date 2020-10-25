<?php

namespace Dev4Press\Plugin\DemoPress\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Library {
	public function __construct() {
	}

	/** @return Library */
	public static function instance() {
		static $instance = array();

		$class = get_called_class();

		if ( ! isset( $instance[ $class ] ) ) {
			$instance[ $class ] = new $class();
		}

		return $instance[ $class ];
	}
}
