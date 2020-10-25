<?php

namespace Dev4Press\Plugin\DEMOPRESS\Admin;

use Dev4Press\Core\UI\Admin\Panel as BasePanel;
use Dev4Press\Plugin\DEMOPRESS\Traits\Panel as TraitPanel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Panel extends BasePanel {
	use TraitPanel;

	public function enqueue_scripts() {
		$this->local_enqueue_scripts( $this->a() );
	}
}
