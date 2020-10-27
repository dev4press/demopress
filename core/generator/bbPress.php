<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use Dev4Press\Plugin\DemoPress\Base\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bbPress extends Generator {
	public $name = 'bbpress';

	protected function init_builders() {

	}

	protected function init_settings() {

	}

	protected function generate_item( $type ) {

	}

	public function get_list_of_types() {
		return array(
			bbp_get_forum_post_type(),
			bbp_get_topic_post_type(),
			bbp_get_reply_post_type()
		);
	}
}
