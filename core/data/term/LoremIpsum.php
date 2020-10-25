<?php

namespace Dev4Press\Plugin\DEMOPRESS\Data\Term;

use Dev4Press\Plugin\DEMOPRESS\Library\LoremIpsum as LibLoremIpsum;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Base {
	public $name = 'loremipsum';

	protected function data( $max ) {
		return LibLoremIpsum::instance()->words( $max );
	}
}
