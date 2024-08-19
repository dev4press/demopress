<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function d4p_plugin_demopress_libs_autoload( $class ) {
	$base = dirname( __FILE__ ) . '/';

	$list = array(
		'Dev4Press\\Generator\\Name\\Random'       => 'random-names-generator/Random.php',
		'Dev4Press\\Generator\\Title\\Random'      => 'random-titles-generator/Random.php',
		'Dev4Press\\Generator\\Image\\Placeholder' => 'placeholder-image-generator/Placeholder.php'
	);

	if ( isset( $list[ $class ] ) ) {
		$path = $base . $list[ $class ];

		if ( file_exists( $path ) ) {
			include( $path );
		}
	}
}

spl_autoload_register( 'd4p_plugin_demopress_libs_autoload' );
