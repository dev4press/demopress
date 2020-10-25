<?php

namespace Dev4Press\Plugin\DEMOPRESS\Data\Title;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DEMOPRESS\Builder\Title;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base extends Title {
	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $type . '-builder-' . $name . '-' . $this->name . '-words', __( "Limit number of words", "demopress" ), __( "The title will have 2 or more words, up to the limit.", "demopress" ), Type::ABSINT, 8 )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'   => 1
			) )
		);
	}

	public function run( $settings = array() ) {
		$defaults = array(
			'min' => 2,
			'max' => 8
		);

		$settings = wp_parse_args( $settings, $defaults );

		$get = rand( $settings['main'], $settings['max'] );

		$words = $this->data( $get );

		return ucwords( $words );
	}

	abstract protected function data( $words );
}
