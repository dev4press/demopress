<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Plugin\DemoPress\Base\Library;
use Dev4Press\Service\Media\Pixabay\Query;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pixabay extends Library {
	private $_object;
	private $_words = array( 'red', 'blue', 'green', 'purple', 'black', 'orange', 'yellow', 'gray' );
	private $_cache = array();

	public function __construct() {
		$this->_object = Query::instance( demopress_settings()->get( 'pixabay_api_key' ) );

		shuffle( $this->_words );
	}

	public function image( $args = array() ) {
		$defaults = array(
			'size'        => 'large',
			'image_type'  => 'photo',
			'q'           => '',
			'orientation' => '',
			'colors'      => '',
			'category'    => '',
			'per_page'    => 200
		);

		$args = wp_parse_args( $args, $defaults );

		$size = $args['size'];
		unset( $args['size'] );

		$words = explode( ',', strtolower( $args['q'] ) );
		$words = array_map( 'trim', $words );
		$words = array_merge( $words, $this->_words );
		$words = array_unique( $words );

		$key = 0;

		$images = $this->find_images( $words[ $key ], $args );

		$image  = false;
		$unique = false;

		while ( $unique === false ) {
			foreach ( $images as $img ) {
				$check = 'pixabay-' . $img->slug . '-' . $img->id;

				if ( ! in_array( $check, $this->_cache ) && ! demopress_db()->check_if_image_exists( $check ) ) {
					$image          = $img;
					$this->_cache[] = $check;
					break 2;
				}
			}

			$key ++;
			$images = $this->find_images( $words[ $key ], $args );
		}

		$i = $image->by_name( $size );

		return array(
			'url'  => $i->url,
			'data' => array(
				'slug'  => 'pixabay-' . $image->slug . '-' . $image->id,
				'name'  => 'pixabay-' . $image->slug . '-' . $image->id . '.' . $image->extension,
				'title' => $image->name
			)
		);
	}

	private function find_images( $query, $args ) {
		$args['q'] = $query;

		$images = $this->_object->images( $args );

		if ( is_wp_error( $images ) ) {
			return $images;
		} else if ( empty( $images->results ) ) {
			new WP_Error( 'image_failed', __( "No results received.", "demopress" ) );
		}

		shuffle( $images->results );

		return $images->results;
	}
}