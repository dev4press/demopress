<?php

namespace Dev4Press\Plugin\DEMOPRESS\Library;

use Dev4Press\Generator\Text\LoremIpsum as BaseLoremIpsum;
use Dev4Press\Plugin\DEMOPRESS\Base\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Library {
	private $_object;

	public function __construct() {
		$this->_object = new BaseLoremIpsum();
	}

	public function words( $count, $tags = false, $array = false ) {
		return $this->_object->words( $count, $tags, $array );
	}

	public function paragraphs( $count, $tags = false, $array = false ) {
		return $this->_object->paragraphs( $count, $tags, $array );
	}
}
