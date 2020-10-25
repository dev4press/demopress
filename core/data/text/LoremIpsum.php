<?php

namespace Dev4Press\Plugin\DEMOPRESS\Data\Text;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DEMOPRESS\Builder\Text;
use Dev4Press\Plugin\DEMOPRESS\Library\LoremIpsum as LibLoremIpsum;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Text {
	public $name = 'loremipsum';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $type . '-builder-' . $name . '-' . $this->name . '-paragraphs', __( "Number of paragraphs", "demopress" ), __( "The term will have 1 or more words, up to the limit.", "demopress" ), Type::ABSINT, 3 )->args( array(
				'class' => $class . '-' . $this->name,
				'min'   => 1
			) )
		);
	}

	public function run( $settings = array() ) {
		$defaults = array(
			'paragraphs' => 2,
			'array'      => false
		);

		$settings = wp_parse_args( $settings, $defaults );

		return LibLoremIpsum::instance()->paragraphs( $settings['paragraphs'], false, $settings['array'] );
	}
}
