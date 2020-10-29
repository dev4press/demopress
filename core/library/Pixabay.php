<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Plugin\DemoPress\Base\Library;
use Dev4Press\Service\Media\Pixabay\Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pixabay extends Library {
	private $_object;

	public function __construct() {
		$this->_object = Query::instance(demopress_settings()->get('pixabay_api_key'));
	}
}