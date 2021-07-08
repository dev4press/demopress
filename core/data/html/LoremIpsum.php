<?php

namespace Dev4Press\Plugin\DemoPress\Data\HTML;

use Dev4Press\Plugin\DemoPress\Library\LoremIpsum as LibLoremIpsum;
use Dev4Press\v35\Core\Options\Element as EL;
use Dev4Press\v35\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Base {
	public $name = 'loremipsum';
	public $scope = 'local';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		$_are_blocks_supported = apply_filters( 'demopress_data_text_lorem_ipsum_block_supported', true );

		$_the_settings = parent::settings( $base, $type, $name, $class, $hidden );

		if ( $_are_blocks_supported ) {
			return array_merge( $_the_settings, array(
				EL::i( $base, $this->el_option_name( $type, $name, 'block' ), __( "Block Editor Ready", "demopress" ), __( "Generated HTML will be formatted for the WordPress block editor. Currently, 'Description lists' are not supported for block formatted HTML.", "demopress" ), Type::BOOLEAN, false )->args( array(
					'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
				) )
			) );
		} else {
			return $_the_settings;
		}
	}

	/**
	 * @param array                                      $settings
	 * @param \Dev4Press\Plugin\DemoPress\Base\Generator $generator
	 *
	 * @return mixed
	 */
	public function run( $settings = array(), $generator = null ) {
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
