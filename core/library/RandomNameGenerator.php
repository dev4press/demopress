<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Generator\Name\Random;
use Dev4Press\Plugin\DemoPress\Base\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RandomNameGenerator extends Library {
	private $_object;

	public function __construct() {
		$this->_object = Random::instance();
	}

	public function get_name() {
		$list = $this->_object->generate_names();

		return $list[0];
	}
}
