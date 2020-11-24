<?php

namespace Dev4Press\Plugin\DemoPress\Data\Text;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LorIpsumNet extends Base {
	public $name = 'loripsumnet';
	public $scope = 'remote';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array_merge( parent::settings( $base, $type, $name, $class, $hidden ), array(
			EL::i( $base, $this->el_option_name( $type, $name, 'more' ), __( "More Settings", "demopress" ), '', Type::CHECKBOXES, array() )->data( 'array', array(
				'allcaps' => __( "All caps", "demopress" ),
				'prude'   => __( "Prude", "demopress" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) )
		) );
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
			'length'     => 'short',
			'more'       => array()
		);

		$settings = wp_parse_args( $settings, $defaults );

		$url = 'https://loripsum.net/api';

		$url .= '/' . $settings['paragraphs'];
		$url .= '/' . $settings['length'];

		$url .= '/plaintext';

		if ( ! empty( $settings['more'] ) ) {
			$url .= '/' . join( '/', $settings['more'] );
		}

		$get = wp_remote_get( $url );

		if ( is_wp_error( $get ) ) {
			return $get;
		} else if ( ! isset( $get['body'] ) ) {
			return new WP_Error( 'remove_get', __( "No response received.", "demopress" ) );
		}

		return trim( $get['body'] );
	}
}
