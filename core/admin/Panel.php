<?php

namespace Dev4Press\Plugin\DemoPress\Admin;

use Dev4Press\Plugin\DemoPress\Traits\Panel as TraitPanel;
use Dev4Press\v35\Core\UI\Admin\Panel as BasePanel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Panel extends BasePanel {
	use TraitPanel;

	public function enqueue_scripts() {
		$this->local_enqueue_scripts( $this->a() );
	}
}
