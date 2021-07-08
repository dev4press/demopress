<?php

namespace Dev4Press\Plugin\DemoPress\Data\Image;

use Dev4Press\Plugin\DemoPress\Library\Pexels;
use Dev4Press\v35\Core\Options\Element as EL;
use Dev4Press\v35\Core\Options\Type;

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

	/**
	 * @param array                                      $settings
	 * @param \Dev4Press\Plugin\DemoPress\Base\Generator $generator
	 *
	 * @return mixed
	 */
	public function run( $settings = array(), $generator = null ) {
		$args = array(
			'query'  => isset( $settings['query'] ) ? $settings['query'] : '',
			'width'  => 1280,
			'height' => 720
		);

		$dim = isset( $settings['dimensions'] ) ? explode( 'x', $settings['dimensions'] ) : false;

		if ( $dim !== false ) {
			$args['width']  = absint( $dim[0] );
			$args['height'] = absint( $dim[1] );
		}

		return Pexels::instance()->image( $args );
	}
}
