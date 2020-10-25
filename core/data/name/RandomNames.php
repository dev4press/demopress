<?php

namespace Dev4Press\Plugin\DemoPress\Data\Name;

use Dev4Press\Plugin\DemoPress\Library\RandomNameGenerator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RandomNames extends Base {
	public $name = 'randomnames';

	protected function data() {
		return RandomNameGenerator::instance()->get_name();
	}
}
