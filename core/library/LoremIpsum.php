<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Generator\Text\LoremIpsum as BaseLoremIpsum;
use Dev4Press\Plugin\DemoPress\Base\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Library {
	private $_object;

	public function __construct() {
		$this->_object = new BaseLoremIpsum();
	}

	public function words( $count ) {
		return $this->_object->words( $count, false, false );
	}

	public function paragraphs( $count ) {
		return $this->_object->paragraphs( $count, false, false );
	}

	public function html( $count, $settings = array() ) {
		return $this->_object->html( $count, $settings, false );
	}
}
