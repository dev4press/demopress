<?php

namespace Dev4Press\Plugin\DemoPress\Data\HTML;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Library\LoremIpsum as LibLoremIpsum;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Base {
	public $name = 'loremipsum';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array_merge( parent::settings( $base, $type, $name, $class, $hidden ), array(
			EL::i( $base, $this->el_option_name( $type, $name, 'block' ), __( "Block Editor Ready", "demopress" ), __( "Generated HTML will be formatted for the WordPress block editor. Currently, 'Description lists' are not supported for block formatted HTML.", "demopress" ), Type::BOOLEAN, false )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) )
		) );
	}

	public function run( $settings = array() ) {
		$defaults = array(
			'paragraphs' => 2,
			'block'      => false,
			'length'     => 'short',
			'html'       => array( 'decorate', 'link', 'headers' )
		);

		$settings = wp_parse_args( $settings, $defaults );

		LibLoremIpsum::instance()->change_length( $settings['length'] );

		return LibLoremIpsum::instance()->html( $settings['paragraphs'], $settings['html'], $settings['block'] );
	}
}
