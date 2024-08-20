<?php

namespace Dev4Press\Plugin\DemoPress\Admin;

use Dev4Press\v50\Core\Quick\WPR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AJAX {
	public function __construct() {
		add_action( 'wp_ajax_demopress_get_generator_status', array( $this, 'get_status' ) );
	}

	public static function instance() : AJAX {
		static $_store_admin_ajax = null;

		if ( ! isset( $_store_admin_ajax ) ) {
			$_store_admin_ajax = new AJAX();
		}

		return $_store_admin_ajax;
	}

	public function get_status() {
		WPR::check_ajax_referer( 'demopress_get_generator_status', $_REQUEST['nonce'] );

		demopress_gen()->check_health();

		$render = '<pre>' . join( PHP_EOL, demopress_gen()->format_log_list() ) . '</pre>';

		die( $render );
	}
}


