<?php

namespace Dev4Press\Plugin\DemoPress\Data\Name;

use Dev4Press\Plugin\DemoPress\Library\Randomizer as LibRandomizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Randomizer extends Base {
	public $name = 'randomizer';

	protected function data() {
		return LibRandomizer::instance()->words( 2 );
	}
}
