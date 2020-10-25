<?php

namespace Dev4Press\Plugin\DemoPress\Data\Name;

use Dev4Press\Plugin\DemoPress\Library\LoremIpsum as LibLoremIpsum;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Base {
	public $name = 'loremipsum';

	protected function data() {
		return LibLoremIpsum::instance()->words( 2 );
	}
}
