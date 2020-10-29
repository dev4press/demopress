<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Plugin\DemoPress\Base\Library;
use Dev4Press\Service\Media\Pexels\Query;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pexels extends Library {
	private $_object;

	public function __construct() {
		$this->_object = Query::instance(demopress_settings()->get('pexels_api_key'));
	}

	public function image( $args = array() ) {
		$defaults = array(
			'width'  => 1280,
			'height' => 720,
			'query'  => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$images = $this->_object->images(array(
			'query'    => $args['query'],
			'page'     => 1,
			'per_page' => 80
		));

		if (is_wp_error($images)) {
			return $images;
		} else if (empty($images->results)) {
			new WP_Error( 'image_failed', __("No results received.") );
		}

		$id = array_rand($images->results, 1);
		$img = $images->results[$id];

		return $img->custom($args['width'], $args['height']);
	}
}