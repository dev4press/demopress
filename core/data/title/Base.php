<?php

namespace Dev4Press\Plugin\DemoPress\Data\Title;

use Dev4Press\Plugin\DemoPress\Builder\Title;
use Dev4Press\v35\Core\Options\Element as EL;
use Dev4Press\v35\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base extends Title {
	public $scope = 'local';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $this->el_option_name( $type, $name, 'min' ), __( "Number of words: From", "demopress" ), __( "The title will have 2 or more words, up to the limit.", "demopress" ), Type::ABSINT, 3 )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'max' ), __( "Number of words: To", "demopress" ), __( "The title will have 2 or more words, up to the limit.", "demopress" ), Type::ABSINT, 8 )->args( array(
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
		$defaults = array(
			'min' => 2,
			'max' => 8
		);

		$settings = wp_parse_args( $settings, $defaults );

		$words = mt_rand( $settings['min'], $settings['max'] );

		return ucwords( $this->data( $words ) );
	}

	abstract protected function data( $words );
}
