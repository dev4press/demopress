<?php

namespace Dev4Press\Plugin\DemoPress\Data\Term;

use Dev4Press\Plugin\DemoPress\Library\Randomizer as LibRandomizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Randomizer extends Base {
	public $name = 'randomizer';

	protected function data( $max ) {
		return LibRandomizer::instance()->words( $max );
	}
}
