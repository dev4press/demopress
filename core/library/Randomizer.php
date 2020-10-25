<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Generator\Text\Randomizer as BaseRandomizer;
use Dev4Press\Plugin\DemoPress\Base\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Randomizer extends Library {
	private $_object;

	public function __construct() {
		$this->_object = new BaseRandomizer();
	}

	public function words( $count, $tags = false, $array = false ) {
		return $this->_object->words( $count, $tags, $array );
	}

	public function paragraphs( $count, $tags = false, $array = false ) {
		return $this->_object->paragraphs( $count, $tags, $array );
	}
}