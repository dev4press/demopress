<?php

namespace Dev4Press\Plugin\DEMOPRESS\Admin;

use Dev4Press\Core\Admin\Submenu\Plugin as BasePlugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends BasePlugin {
	public $plugin = 'demopress';
	public $plugin_prefix = 'demopress';
	public $plugin_menu = 'DemoPress';
	public $plugin_title = 'DemoPress';

	public function constructor() {
		$this->url  = DEMOPRESS_URL;
		$this->path = DEMOPRESS_PATH;
	}

	public function after_setup_theme() {
		$this->setup_items = array(
			'install' => array(
				'title' => __( "Install", "gd-topic-polls" ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( "Before you continue, make sure plugin installation was successful.", "gd-topic-polls" ),
				'class' => '\\Dev4Press\\Plugin\\DEMOPRESS\\Admin\\Panel\\Install'
			),
			'update'  => array(
				'title' => __( "Update", "gd-topic-polls" ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( "Before you continue, make sure plugin was successfully updated.", "gd-topic-polls" ),
				'class' => '\\Dev4Press\\Plugin\\DEMOPRESS\\Admin\\Panel\\Update'
			)
		);

		$this->menu_items = array(
			'dashboard' => array(
				'title' => __( "Overview", "gd-topic-polls" ),
				'icon'  => 'ui-home',
				'class' => '\\Dev4Press\\Plugin\\DEMOPRESS\\Admin\\Panel\\Dashboard'
			),
			'about'     => array(
				'title' => __( "About", "gd-topic-polls" ),
				'icon'  => 'ui-info',
				'class' => '\\Dev4Press\\Plugin\\DEMOPRESS\\Admin\\Panel\\About'
			),
			'settings'  => array(
				'title' => __( "Settings", "gd-topic-polls" ),
				'icon'  => 'ui-cog',
				'class' => '\\Dev4Press\\Plugin\\DEMOPRESS\\Admin\\Panel\\Settings'
			),
			'tools'     => array(
				'title' => __( "Tools", "gd-topic-polls" ),
				'icon'  => 'ui-wrench',
				'class' => '\\Dev4Press\\Plugin\\DEMOPRESS\\Admin\\Panel\\Tools'
			)
		);
	}

	public function svg_icon() {
		return demopress()->svg_icon;
	}

	public function run_getback() {
		new GetBack( $this );
	}

	public function run_postback() {
		new PostBack( $this );
	}

	public function message_process( $code, $msg ) {
		switch ( $code ) {
			case 'gen-stopped':
				$msg['message'] = __( "Poll votes are all removed.", "gd-topic-polls" );
				break;
			case 'gen-removed':
				$msg['message'] = __( "Votes deletion completed.", "gd-topic-polls" );
				break;
		}

		return $msg;
	}

	public function settings() {
		return demopress_settings();
	}

	public function settings_definitions() {
		return Settings::instance();
	}
}
