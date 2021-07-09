<?php

namespace Dev4Press\Plugin\DemoPress\Data\Image;

use Dev4Press\Plugin\DemoPress\Library\Placeholder as LibPlaceholder;
use Dev4Press\v35\Core\Options\Element as EL;
use Dev4Press\v35\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Placeholder extends Base {
	public $name = 'placeholder';
	public $scope = 'local';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $this->el_option_name( $type, $name, 'dimensions' ), __( "Dimensions", "demopress" ), __( "Do not set this to dimensions over 3000 by 2000 pixels.", "demopress" ), Type::X_BY_Y, '1280x720' )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'colors' ), __( "Colors", "demopress" ), '', Type::SELECT, 'dark-random' )->data( 'array', array(
				'dark-random'  => __( "Random Darker Background", "demopress" ),
				'light-random' => __( "Random Lighter Background", "demopress" )
			) )->args( array(
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
			'path' => $this->get_temp_dir(),
			'colors' => $settings['colors'] ?? 'dark-random'
		);

		$dim = isset( $settings['dimensions'] ) ? explode( 'x', $settings['dimensions'] ) : false;

		if ( $dim !== false ) {
			$args['width']  = absint( $dim[0] );
			$args['height'] = absint( $dim[1] );
		}

		return LibPlaceholder::instance()->image( $args );
	}
}