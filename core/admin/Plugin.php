<?php

namespace Dev4Press\Plugin\DemoPress\Admin;

use Dev4Press\v35\Core\Admin\Submenu\Plugin as BasePlugin;

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

	public static function instance() : Plugin {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Plugin();
		}

		return $instance;
	}

	public function after_setup_theme() {
		$this->setup_items = array(
			'install' => array(
				'title' => __( "Install", "demopress" ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( "Before you continue, make sure plugin installation was successful.", "demopress" ),
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Install'
			),
			'update'  => array(
				'title' => __( "Update", "demopress" ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( "Before you continue, make sure plugin was successfully updated.", "demopress" ),
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Update'
			)
		);

		$this->menu_items = array(
			'dashboard' => array(
				'title' => __( "Overview", "demopress" ),
				'icon'  => 'ui-home',
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Dashboard'
			),
			'about'     => array(
				'title' => __( "About", "demopress" ),
				'icon'  => 'ui-info',
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\About'
			),
			'settings'  => array(
				'title' => __( "Settings", "demopress" ),
				'icon'  => 'ui-cog',
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Settings'
			),
			'tools'     => array(
				'title' => __( "Tools", "demopress" ),
				'icon'  => 'ui-wrench',
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Tools'
			)
		);
	}

	public function svg_icon() : string {
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
			case 'cleanup-completed':
				$msg['message'] = __( "The data removal has been completed.", "demopress" );
				break;
			case 'gen-stopped':
				$msg['message'] = __( "The Generator process has been stopped.", "demopress" );
				break;
			case 'gen-added':
				$msg['message'] = __( "New Generator process has been added.", "demopress" );
				break;
			case 'gen-removed':
				$msg['message'] = __( "The Generator process has been reset.", "demopress" );
				break;
			case 'gen-error':
				$msg['message'] = __( "The Generator process has stopped due to an error.", "demopress" );
				$msg['color']   = 'error';
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
