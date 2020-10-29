<?php

namespace Dev4Press\Plugin\DemoPress\Traits;

trait Panel {
	/** @param $admin \Dev4Press\Plugin\DemoPress\Admin\Plugin */
	protected function local_enqueue_scripts( $admin ) {
		$admin->css( 'admin' );
		$admin->js( 'admin' );

		wp_localize_script( 'demopress-admin', 'demopress_data', array(
			'nonce' => wp_create_nonce( 'demopress_get_generator_status' )
		) );
	}
}
