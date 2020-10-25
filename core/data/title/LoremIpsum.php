<?php

namespace Dev4Press\Plugin\DEMOPRESS\Data\Title;

use Dev4Press\Plugin\DEMOPRESS\Library\LoremIpsum as LibLoremIpsum;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Base {
	public $name = 'loremipsum';

	protected function data( $words ) {
		return LibLoremIpsum::instance()->words( $words );
	}
}
