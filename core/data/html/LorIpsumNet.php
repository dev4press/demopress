<?php

namespace Dev4Press\Plugin\DemoPress\Data\HTML;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LorIpsumNet extends Base {
	public $name = 'loripsumnet';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array_merge( parent::settings( $base, $type, $name, $class, $hidden ), array(
			EL::i( $base, $this->el_option_name( $type, $name, 'length' ), __( "Paragraph Length" ), '', Type::SELECT, 'short' )->data( 'array', array(
				'short'  => __( "Short" ),
				'medium' => __( "Medium" ),
				'long'   => __( "Long" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'more' ), __( "More Settings" ), '', Type::CHECKBOXES, array() )->data( 'array', array(
				'allcaps' => __( "All caps" ),
				'prude'   => __( "Prude" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) )
		) );
	}

	public function run( $settings = array() ) {
		$defaults = array(
			'paragraphs' => 2,
			'length'     => 'short',
			'html'       => array( 'decorate', 'link', 'headers' ),
			'more'       => array()
		);

		$settings = wp_parse_args( $settings, $defaults );

		$url = 'https://loripsum.net/api';

		$url .= '/' . $settings['paragraphs'];
		$url .= '/' . $settings['length'];

		if ( ! empty( $settings['html'] ) ) {
			$url .= '/' . join( '/', $settings['html'] );
		}

		if ( ! empty( $settings['more'] ) ) {
			$url .= '/' . join( '/', $settings['more'] );
		}

		$get = wp_remote_get( $url );

		if ( is_wp_error( $get ) ) {
			return $get;
		} else if ( ! isset( $get['body'] ) ) {
			return new WP_Error( 'remove_get', __( "No response received." ) );
		}

		return $get['body'];
	}
}
