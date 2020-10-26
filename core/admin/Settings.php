<?php

namespace Dev4Press\Plugin\DemoPress\Admin;

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
								EL::i( 'settings', 'pixabay_api_key', __( "API Key", "demopress" ), __( "To use this website, you need access to their API and a valid API key.", "demopress" ).' <a rel="nofollow noopener" target="_blank" href="https://pixabay.com/service/about/api/">'.__( "Get API access", "demopress" ).'</a>', Type::TEXT, $this->value( 'pixabay_api_key', 'settings' ) ),
								EL::i( 'settings', 'pixabay_full_access', __( "Full Access", "demopress" ), __( "If your API account is approved for full access, you will have access to full HD and full image sizes. Without it, images retrieved by API will be limited to 1280px.", "demopress" ), Type::BOOLEAN, $this->value( 'pixabay_full_access', 'settings' ) )
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
								EL::i( 'settings', 'pexels_api_key', __( "API Key", "demopress" ), __( "To use this website, you need access to their API and a valid API key.", "demopress" ).' <a rel="nofollow noopener" target="_blank" href="https://www.pexels.com/api/">'.__( "Get API access", "demopress" ).'</a>', Type::TEXT, $this->value( 'pexels_api_key', 'settings' ) )
							)
						)
					)
				)
			)
		) );
	}
}
