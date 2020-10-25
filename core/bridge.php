<?php

use Dev4Press\Plugin\DEMOPRESS\Admin\AJAX;
use Dev4Press\Plugin\DEMOPRESS\Admin\Plugin as AdminPlugin;
use Dev4Press\Plugin\DEMOPRESS\Basic\DB;
use Dev4Press\Plugin\DEMOPRESS\Basic\Generator;
use Dev4Press\Plugin\DEMOPRESS\Basic\Plugin;
use Dev4Press\Plugin\DEMOPRESS\Basic\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @return \Dev4Press\Core\Plugins\Core|\Dev4Press\Plugin\DEMOPRESS\Basic\Plugin */
function demopress() {
	return Plugin::instance();
}

/** @return \Dev4Press\Core\Plugins\Settings|\Dev4Press\Plugin\DEMOPRESS\Basic\Settings */
function demopress_settings() {
	return Settings::instance();
}

/** @return \Dev4Press\Core\Plugins\DB|\Dev4Press\Plugin\DEMOPRESS\Basic\DB */
function demopress_db() {
	return DB::instance();
}

/** @return \Dev4Press\Plugin\DEMOPRESS\Basic\Generator */
function demopress_gen() {
	return Generator::instance();
}

/** @return \Dev4Press\Plugin\DEMOPRESS\Admin\AJAX */
function demopress_ajax() {
	return AJAX::instance();
}

/** @return \Dev4Press\Plugin\DEMOPRESS\Admin\Plugin */
function demopress_admin() {
	return AdminPlugin::instance();
}
