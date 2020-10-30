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
	private $_words = array( 'red', 'blue', 'green', 'purple', 'black', 'orange', 'yellow', 'gray' );
	private $_cache = array();

	public function __construct() {
		$this->_object = Query::instance( demopress_settings()->get( 'pexels_api_key' ) );

		shuffle($this->_words);
	}

	public function image( $args = array() ) {
		$defaults = array(
			'width'  => 1280,
			'height' => 720,
			'query'  => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$words = explode( ',', strtolower( $args['query'] ) );
		$words = array_map( 'trim', $words );
		$words = array_merge( $words, $this->_words );
		$words = array_unique( $words );

		$key = 0;

		$images = $this->find_images( $words[ $key ] );

		$image  = false;
		$unique = false;

		while ( $unique === false ) {
			foreach ( $images as $img ) {
				$check = 'pexels-' . $img->slug . '-' . $img->id;

				if (! in_array($check, $this->_cache) && ! demopress_db()->check_if_image_exists( $check ) ) {
					$image = $img;
					$this->_cache[] = $check;
					break 2;
				}
			}

			$key++;
			$images = $this->find_images( $words[ $key ] );
		}

		return array(
			'url'  => $image->custom( $args['width'], $args['height'] ),
			'data' => array(
				'slug'  => 'pexels-' . $image->slug . '-' . $image->id,
				'name'  => 'pexels-' . $image->slug . '-' . $image->id . '.' . $image->extension,
				'title' => $image->name
			)
		);
	}

	private function find_images( $query ) {
		$images = $this->_object->images( array(
			'query'    => $query,
			'page'     => 1,
			'per_page' => 80
		) );

		if ( is_wp_error( $images ) ) {
			return $images;
		} else if ( empty( $images->results ) ) {
			new WP_Error( 'image_failed', __( "No results received." ) );
		}

		shuffle($images->results);

		return $images->results;
	}
}