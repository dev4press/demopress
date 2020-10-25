<?php

namespace Dev4Press\Plugin\DEMOPRESS\Generator;

use Dev4Press\Plugin\DEMOPRESS\Base\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Comments extends Generator {
	public $name = 'comments';

	protected function init_builders() {
		$this->builders['content'] = demopress()->find_builders( 'text', array( 'plain' ) );
	}

	protected function init_settings() {

	}

	protected function generate_item( $type ) {

	}
}
