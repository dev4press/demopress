<?php

namespace Dev4Press\Plugin\DemoPress\Data\Image;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Library\Placeholder as LibPlaceholder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Placeholder extends Base {
	public $name = 'placeholder';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $this->el_option_name( $type, $name, 'dimensions' ), __( "Dimensions", "demopress" ), __( "Do not set this to dimensions over 3000 by 2000 pixels.", "demopress" ), Type::X_BY_Y, '1280x720' )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) )
		);
	}

	public function run( $settings = array() ) {
		$args = array(
			'path' => $this->get_temp_dir()
		);

		$dim = isset( $settings['dimensions'] ) ? explode( 'x', $settings['dimensions'] ) : false;

		if ( $dim !== false ) {
			$args['width']  = absint( $dim[0] );
			$args['height'] = absint( $dim[1] );
		}

		return LibPlaceholder::instance()->image( $args );
	}
}