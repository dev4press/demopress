<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Plugin\DemoPress\Base\Library;
use Dev4Press\Service\Media\Picsum\Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Picsum extends Library {
	private $_object;

	public function __construct() {
		$this->_object = Query::instance();
	}
}