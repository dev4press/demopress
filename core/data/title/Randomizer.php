<?php

namespace Dev4Press\Plugin\DEMOPRESS\Data\Title;

use Dev4Press\Plugin\DEMOPRESS\Library\Randomizer as LibRandomizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Randomizer extends Base {
	public $name = 'loremipsum';

	protected function data( $words ) {
		return LibRandomizer::instance()->words( $words );
	}
}
