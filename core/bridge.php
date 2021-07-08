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

function demopress() : Plugin {
	return Plugin::instance();
}

function demopress_settings() : Settings {
	return Settings::instance();
}

function demopress_db() : DB {
	return DB::instance();
}

function demopress_gen() : Generator {
	return Generator::instance();
}

function demopress_ajax() : AJAX {
	return AJAX::instance();
}

function demopress_admin() : AdminPlugin {
	return AdminPlugin::instance();
}
