<?php

namespace Dev4Press\Plugin\DEMOPRESS\Admin;

use Dev4Press\Core\Admin\GetBack as BaseGetBack;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GetBack extends BaseGetBack {
	protected function process() {
		parent::process();

		if ( $this->a()->panel === 'generator' ) {
			if ( $this->is_single_action( 'stoptask' ) ) {
				$this->task_stop();
			}

			if ( $this->is_single_action( 'resettask' ) ) {
				$this->task_reset();
			}
		}
	}

	public function task_stop() {
		check_ajax_referer( 'demopress-task-stop' );

		if ( demopress_gen()->is_running() ) {
			demopress_gen()->stop_task();
		}

		$url = $this->a()->current_url( true ) . '&message=gen-stopped';

		wp_redirect( $url );
		exit;
	}

	public function task_reset() {
		check_ajax_referer( 'demopress-task-reset' );

		if ( ! demopress_gen()->is_idle() ) {
			demopress_gen()->reset_task();
		}

		$url = $this->a()->current_url( true ) . '&message=gen-removed';

		wp_redirect( $url );
		exit;
	}
}
