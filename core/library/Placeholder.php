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
			'width'      => 1280,
			'height'     => 720,
			'colors'     => 'dark-random',
			'text'       => 'size',
			'rectangles' => 0,
			'name'       => false,
			'path'       => false,
			'post_title' => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$this->_object->size( $args['width'], $args['height'] )
		              ->colors( $args['colors'] )
		              ->rectangles( $args['rectangles'], 'big', $args['colors'] )
		              ->render( $args['text'], $args['post_title'] )
		              ->font( false, false, 48 );

		try {
			$file = $this->_object->generate( $args['name'], $args['path'] );
		} catch ( Exception $e ) {
			$file = new WP_Error( 'image_failed', $e->getMessage() );
		}

		return array(
			'path' => $file,
			'data' => array()
		);
	}
}