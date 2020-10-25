<?php

namespace Dev4Press\Plugin\DEMOPRESS\Library;

use Dev4Press\Plugin\DEMOPRESS\Base\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RandomNameGenerator extends Library {
	private $_object;

	public function __construct() {
		require_once( DEMOPRESS_PATH . 'libs/php-random-name-generator/randomNameGenerator.php' );

		$this->_object = new \randomNameGenerator();
	}

	public function get_name() {
		$list = $this->_object->generateNames( 1 );

		return $list[0];
	}
}
