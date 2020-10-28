<?php

namespace Dev4Press\Plugin\DemoPress\Data\Image;

use Dev4Press\Plugin\DemoPress\Builder\Image;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base extends Image {
	public function get_temp_dir() {
		return get_temp_dir();
	}
}