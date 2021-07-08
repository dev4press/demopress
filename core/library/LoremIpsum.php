<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Plugin\DemoPress\Base\Library;
use Dev4Press\v35\Generator\Text\LoremIpsum as BaseLoremIpsum;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Library {
	private $_object;

	public function __construct() {
		$this->_object = new BaseLoremIpsum();
	}

	public function words( $count ) {
		return $this->_object->protected_first()->words( $count, false, false );
	}

	public function change_length( $method ) {
		$this->_object->set_paragraph_gauss( $method );
	}

	public function paragraphs( $count ) {
		return $this->_object->paragraphs( $count, false, false );
	}

	public function html( $count, $settings = array(), $block_formatted = false ) {
		return $this->_object->html( $count, $settings, $block_formatted, false );
	}
}
