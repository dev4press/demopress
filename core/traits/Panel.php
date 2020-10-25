<?php

namespace Dev4Press\Plugin\DEMOPRESS\Traits;

trait Panel {
	/** @param $admin \Dev4Press\Plugin\DEMOPRESS\Admin\Plugin */
	protected function local_enqueue_scripts( $admin ) {
		$admin->css( 'admin' );
		$admin->js( 'admin' );
	}
}
