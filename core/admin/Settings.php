<?php

namespace Dev4Press\Plugin\DemoPress\Admin;

use Dev4Press\v35\Core\Options\Element as EL;
use Dev4Press\v35\Core\Options\Settings as BaseSettings;
use Dev4Press\v35\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends BaseSettings {
	public static function instance() : Settings {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Settings();
		}

		return $instance;
	}

	protected function value( $name, $group = 'settings', $default = null ) {
		return demopress_settings()->get( $name, $group, $default );
	}

	protected function init() {
		$this->settings = apply_filters( 'demopress_admin_internal_settings', array(
			'global'   => array(
				'global_remote' => array(
					'name'     => __( "Remote Builders", "demopress" ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'remote_enabled', __( "Status", "demopress" ), __( "If you want to use only generators that don't depend on the remote third party serivces to generate content or images, disable this option.", "demopress" ), Type::BOOLEAN, $this->value( 'remote_enabled', 'settings' ) )
							)
						)
					)
				)
			),
			'api_keys' => array(
				'api_pixabay' => array(
					'name'     => __( "Pixabay.com", "demopress" ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'pixabay_api_key', __( "API Key", "demopress" ), __( "To use this website, you need access to their API and a valid API key.", "demopress" ) . ' <a rel="nofollow noopener" target="_blank" href="https://pixabay.com/service/about/api/">' . __( "Get API access", "demopress" ) . '</a>', Type::TEXT, $this->value( 'pixabay_api_key', 'settings' ) ),
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
								EL::i( 'settings', 'pexels_api_key', __( "API Key", "demopress" ), __( "To use this website, you need access to their API and a valid API key.", "demopress" ) . ' <a rel="nofollow noopener" target="_blank" href="https://www.pexels.com/api/">' . __( "Get API access", "demopress" ) . '</a>', Type::TEXT, $this->value( 'pexels_api_key', 'settings' ) )
							)
						)
					)
				)
			)
		) );
	}
}
