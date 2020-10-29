<?php

namespace Dev4Press\Plugin\DemoPress\Data\Image;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;

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
		$defaults = array(
			'dimensions' => '1280x720',
			'query' => ''
		);

		$settings = wp_parse_args($settings, $defaults);


	}
}
