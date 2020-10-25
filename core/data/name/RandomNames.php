<?php

namespace Dev4Press\Plugin\DEMOPRESS\Data\Name;

use Dev4Press\Plugin\DEMOPRESS\Library\RandomNameGenerator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RandomNames extends Base {
	public $name = 'randomnames';

	protected function data() {
		return RandomNameGenerator::instance()->get_name();
	}
}
