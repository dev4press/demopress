<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use Dev4Press\Plugin\DemoPress\Base\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bbPress extends Generator {
	public $name = 'bbpress';

	public function get_list_of_types( $return = 'objects' ) {
		$post_types = demopress_get_bbpress_post_types();

		return $return == 'keys' ? array_keys( $post_types ) : $post_types;
	}

	protected function init_builders() {

	}

	protected function init_settings() {

	}

	protected function generate_item( $type ) {

	}
}
