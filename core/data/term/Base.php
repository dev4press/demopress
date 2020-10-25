<?php

namespace Dev4Press\Plugin\DEMOPRESS\Data\Term;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DEMOPRESS\Builder\Term;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base extends Term {
	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $type . '-builder-' . $name . '-' . $this->name . '-words', __( "Limit number of words", "demopress" ), __( "The term will have 1 or more words, up to the limit.", "demopress" ), Type::ABSINT, 3 )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'   => 1
			) )
		);
	}

	public function run( $settings = array() ) {
		$defaults = array(
			'words'               => 3,
			'random_words_number' => true
		);

		$settings = wp_parse_args( $settings, $defaults );

		$max = $settings['words'];
		if ( $settings['random_words_number'] ) {
			$max = rand( 1, $settings['words'] );
		}

		$words = $this->data( $max );

		return ucwords( $words );
	}

	abstract protected function data( $max );
}
