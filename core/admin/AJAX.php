<?php

namespace Dev4Press\Plugin\DemoPress\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AJAX {
	public function __construct() {
		add_action( 'wp_ajax_demopress_get_generator_status', array( $this, 'get_status' ) );
	}

	public static function instance(): AJAX {
		static $_store_admin_ajax = null;

		if ( ! isset( $_store_admin_ajax ) ) {
			$_store_admin_ajax = new AJAX();
		}

		return $_store_admin_ajax;
	}

	public function get_status() {
		if ( ! current_user_can( demopress_admin()->get_panel_cap( 'dashboard' ) )
		     || ! is_string( $_REQUEST['nonce'] ?? null )
		     || ! check_ajax_referer( 'demopress_get_generator_status', 'nonce', false ) ) {
			wp_send_json_error( null, 403 );
		}

		demopress_gen()->check_health();

		wp_send_json_success( implode( PHP_EOL, demopress_gen()->format_log_list() ) );
	}
}
