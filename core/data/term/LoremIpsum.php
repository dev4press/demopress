<?php

namespace Dev4Press\Plugin\DemoPress\Data\Term;

use Dev4Press\Plugin\DemoPress\Library\LoremIpsum as LibLoremIpsum;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Base {
	public $name = 'loremipsum';

	protected function data( $max ) {
		return LibLoremIpsum::instance()->words( $max );
	}
}
