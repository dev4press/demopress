<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Generator\Image\Placeholder as BasePlaceholder;
use Dev4Press\Plugin\DemoPress\Base\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Placeholder extends Library {
	private $_object;

	public function __construct() {
		$this->_object = BasePlaceholder::instance();
	}
}