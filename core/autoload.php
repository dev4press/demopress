<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function d4p_plugin_demopress_autoload( $class ) {
	$path = dirname( __FILE__ ) . '/';
	$base = 'Dev4Press\\Plugin\\DemoPress\\';

	dev4press_v49_autoload_for_plugin($class, $base, $path);
}

spl_autoload_register( 'd4p_plugin_demopress_autoload' );
