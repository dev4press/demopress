<?php

use Dev4Press\Plugin\DemoPress\Admin\AJAX;
use Dev4Press\Plugin\DemoPress\Admin\Plugin as AdminPlugin;
use Dev4Press\Plugin\DemoPress\Basic\DB;
use Dev4Press\Plugin\DemoPress\Basic\Generator;
use Dev4Press\Plugin\DemoPress\Basic\Plugin;
use Dev4Press\Plugin\DemoPress\Basic\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @return \Dev4Press\Core\Plugins\Core|\Dev4Press\Plugin\DemoPress\Basic\Plugin */
function demopress() {
	return Plugin::instance();
}

/** @return \Dev4Press\Core\Plugins\Settings|\Dev4Press\Plugin\DemoPress\Basic\Settings */
function demopress_settings() {
	return Settings::instance();
}

/** @return \Dev4Press\Core\Plugins\DB|\Dev4Press\Plugin\DemoPress\Basic\DB */
function demopress_db() {
	return DB::instance();
}

/** @return \Dev4Press\Plugin\DemoPress\Basic\Generator */
function demopress_gen() {
	return Generator::instance();
}

/** @return \Dev4Press\Plugin\DemoPress\Admin\AJAX */
function demopress_ajax() {
	return AJAX::instance();
}

/** @return \Dev4Press\Plugin\DemoPress\Admin\Plugin */
function demopress_admin() {
	return AdminPlugin::instance();
}
