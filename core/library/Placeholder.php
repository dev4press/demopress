<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Generator\Image\Placeholder as BasePlaceholder;
use Dev4Press\Plugin\DemoPress\Base\Library;
use Exception;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Placeholder extends Library {
	private $_object;

	public function __construct() {
		$this->_object = BasePlaceholder::instance();
	}

	public function image( $args = array() ) {
		$defaults = array(
			'width'  => 1280,
			'height' => 1280,
			'name'   => false,
			'path'   => false
		);

		$args = wp_parse_args( $args, $defaults );

		$this->_object->size( $args['width'], $args['height'] );

		try {
			$file = $this->_object->generate( $args['name'], $args['path'] );
		} catch ( Exception $e ) {
			$file = new WP_Error( 'image_faild', $e->getMessage() );
		}

		return $file;
	}
}