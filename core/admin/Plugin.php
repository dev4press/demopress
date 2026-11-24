<?php

namespace Dev4Press\Plugin\DemoPress\Admin;

use Dev4Press\v56\Core\Admin\Submenu\Plugin as BasePlugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends BasePlugin {
	public string $plugin = 'demopress';
	public string $plugin_prefix = 'demopress';
	public string $plugin_menu = 'DemoPress';
	public string $plugin_title = 'DemoPress';
	public bool $buy_me_a_coffee = true;

	public bool $auto_mod_interface_colors = true;

	public function constructor() : void {
		$this->url  = DEMOPRESS_URL;
		$this->path = DEMOPRESS_PATH;
	}

	public function admin_menu_items() : void {
		$this->setup_items = array(
			'install' => array(
				'title' => __( "Install", "demopress" ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( "Before you continue, make sure plugin installation was successful.", "demopress" ),
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Install',
			),
			'update'  => array(
				'title' => __( "Update", "demopress" ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( "Before you continue, make sure plugin was successfully updated.", "demopress" ),
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Update',
			),
		);

		$this->menu_items = array(
			'dashboard' => array(
				'title' => __( "Overview", "demopress" ),
				'icon'  => 'ui-home',
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Dashboard',
			),
			'about'     => array(
				'title' => __( "About", "demopress" ),
				'icon'  => 'ui-info',
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\About',
			),
			'settings'  => array(
				'title' => __( "Settings", "demopress" ),
				'icon'  => 'ui-cog',
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Settings',
			),
			'tools'     => array(
				'title' => __( "Tools", "demopress" ),
				'icon'  => 'ui-wrench',
				'class' => '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Panel\\Tools',
			),
		);
	}

	public function register_scripts_and_styles() : void {
		$script_path = $this->path . 'build/admin.js';
		$script_version = is_file( $script_path ) ? (string) filemtime( $script_path ) : demopress_settings()->file_version();

		$this->enqueue->register( 'css', 'demopress-admin',
			array(
				'path' => 'build/',
				'file' => 'admin',
				'ext'  => 'css',
				'ver'  => demopress_settings()->file_version(),
				'src'  => 'plugin',
			) )->register( 'js', 'demopress-admin',
			array(
				'path' => 'build/',
				'file' => 'admin',
				'ext'  => 'js',
				'ver'  => $script_version,
				'src'  => 'plugin',
			) );
	}

	public function enqueue() {
		$this->e()->css( 'demopress-admin' );
		$this->e()->js( 'demopress-admin' );

		wp_localize_script( $this->e()->prefix() . 'demopress-admin', 'demopress_data', array(
			'nonce' => wp_create_nonce( 'demopress_get_generator_status' ),
		) );
	}

	protected function extra_enqueue_scripts_plugin() : void {
		$this->enqueue();
	}

	public function svg_icon() : string {
		return demopress()->svg_icon;
	}

	public function run_getback() : void {
		new GetBack( $this );
	}

	public function run_postback() : void {
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

	public function plugin() {
		return demopress();
	}

	public function wizard() {
		return null;
	}
}
