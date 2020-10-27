<?php

namespace Dev4Press\Plugin\DemoPress\Admin;

use Dev4Press\Core\Admin\PostBack as BasePostBack;
use Dev4Press\Core\Options\Process;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PostBack extends BasePostBack {
	protected function process() {
		parent::process();

		if ( $this->p() == $this->get_page_name( 'generator' ) ) {
			if (isset($_POST['option_page']) && $_POST['option_page'] == 'demopress-generator') {
				$this->generator();
			}
		}

		do_action( 'demopress_admin_postback_handler', $this->p() );
	}

	protected function generator() {
		check_admin_referer('demopress-generator-options');

		if (!demopress_gen()->is_idle()) {
			wp_redirect($this->a()->current_url().'&message=gen-working');
			exit;
		}

		$input = isset($_POST['demopress_value']) ? (array)$_POST['demopress_value'] : array();

		if (!empty($input)) {
			$gen_input = isset($input['demo-generator-type']) ? d4p_sanitize_key_expanded($input['demo-generator-type']) : '';
			$generator = demopress()->get_generator( $gen_input );

			if (!is_wp_error( $generator )) {
				$process = Process::instance( $this->a()->n(), $this->a()->plugin_prefix )->prepare( $generator->settings_for_processing() )->process();
				$request = $generator->process_request($process[$gen_input]);

				demopress_gen()->new_task($gen_input, $request);

				wp_redirect( $this->a()->current_url() . '&message=gen-added' );
				exit;
			}
		}

		wp_redirect( $this->a()->current_url() . '&message=gen-error' );
		exit;
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
