<?php

namespace Dev4Press\Plugin\DEMOPRESS\Admin;

use Dev4Press\Core\Admin\PostBack as BasePostBack;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PostBack extends BasePostBack {
	protected function process() {
		parent::process();

		do_action( 'demopress_admin_postback_handler', $this->p() );
	}

	protected function remove() {
		$data = $_POST['demopresstools'];

		$remove  = isset( $data['remove'] ) ? (array) $data['remove'] : array();
		$message = 'nothing-removed';

		if ( ! empty( $remove ) ) {
			if ( isset( $remove['settings'] ) && $remove['settings'] == 'on' ) {
				$this->a()->settings()->remove_plugin_settings_by_group( 'settings' );
			}

			if ( isset( $remove['objects'] ) && $remove['objects'] == 'on' ) {
				$this->a()->settings()->remove_plugin_settings_by_group( 'objects' );
			}

			if ( isset( $remove['disable'] ) && $remove['disable'] == 'on' ) {
				demopress()->deactivate();

				wp_redirect( admin_url( 'plugins.php' ) );
				exit;
			}

			$message = 'removed';
		}

		wp_redirect( $this->a()->current_url() . '&message=' . $message );
		exit;
	}
}
