<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\Core\Plugins\Settings as BaseSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends BaseSettings {
	public $base = 'demopress';

	public $settings = array(
		'core'     => array(
			'activated' => 0
		),
		'ctrl'     => array(
			'stop' => false
		),
		'task'     => array(
			'status'   => 'idle',
			'type'     => '',
			'started'  => 0,
			'ended'    => 0,
			'last'     => 0,
			'settings' => array(),
			'progress' => array(),
			'log'      => array()
		),
		'settings' => array(
			'pixabay_api_key' => '',
			'pixabay_full_access' => false,
			'pexels_api_key'  => ''
		)
	);

	protected function constructor() {
		$this->info = new Information();

		add_action( 'demopress_load_settings', array( $this, 'init' ) );
	}

	protected function _name( $name ) {
		return 'dev4press_' . $this->info->code . '_' . $name;
	}
}