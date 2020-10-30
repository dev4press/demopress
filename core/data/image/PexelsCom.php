<?php

namespace Dev4Press\Plugin\DemoPress\Data\Image;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Library\Pexels;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PexelsCom extends Base {
	public $name = 'pexelscom';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $this->el_option_name( $type, $name, 'dimensions' ), __( "Dimensions", "demopress" ), __( "Do not set this to dimensions over 3000 by 2000 pixels.", "demopress" ), Type::X_BY_Y, '1280x720' )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'query' ), __( "Search query", "demopress" ), '', Type::TEXT, 'wordpress' )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) )
		);
	}

	public function run( $settings = array() ) {
		$args = array(
			'query' => isset($settings['query']) ? $settings['query'] : '',
			'width' => 1280,
			'height' => 720
		);

		$dim = isset( $settings['dimensions'] ) ? explode( 'x', $settings['dimensions'] ) : false;

		if ( $dim !== false ) {
			$args['width']  = absint( $dim[0] );
			$args['height'] = absint( $dim[1] );
		}

		return Pexels::instance()->image($args);
	}
}
