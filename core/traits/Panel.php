<?php

namespace Dev4Press\Plugin\DemoPress\Traits;

trait Panel {
	/** @param $admin \Dev4Press\Plugin\DemoPress\Admin\Plugin */
	protected function local_enqueue_scripts( $admin ) {
		$admin->css( 'admin' );
		$admin->js( 'admin' );
	}
}
