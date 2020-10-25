<?php

namespace Dev4Press\Plugin\DEMOPRESS\Admin;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Settings as BaseSettings;
use Dev4Press\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends BaseSettings {
	protected function value( $name, $group = 'settings', $default = null ) {
		return demopress_settings()->get( $name, $group, $default );
	}

	protected function init() {
		$this->settings = apply_filters( 'demopress_admin_internal_settings', array(
			'api_keys' => array(
				'api_pixabay' => array(
					'name'     => __( "Pixabay.com", "demopress" ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'pixabay_api_key', __( "API Key", "demopress" ), '', Type::TEXT, $this->value( 'pixabay_api_key', 'settings' ) )
							)
						)
					)
				),
				'api_pexels'  => array(
					'name'     => __( "Pexels.com", "demopress" ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'pexels_api_key', __( "API Key", "demopress" ), '', Type::TEXT, $this->value( 'pexels_api_key', 'settings' ) )
							)
						)
					)
				)
			)
		) );
	}
}
