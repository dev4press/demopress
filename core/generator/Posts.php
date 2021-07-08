<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Posts extends Content {
	public $name = 'posts';

	public function get_list_of_types( $return = 'objects' ) {
		$post_types = demopress_get_post_types();

		return $return == 'keys' ? array_keys( $post_types ) : $post_types;
	}

	protected function init_builders() {
		$this->builders['title']    = array(
			'type' => 'title',
			'list' => demopress()->find_builders( 'title' )
		);
		$this->builders['content']  = array(
			'type' => 'html',
			'list' => demopress()->find_builders( 'html' )
		);
		$this->builders['excerpt']  = array(
			'type' => 'text',
			'list' => demopress()->find_builders( 'text' )
		);
		$this->builders['featured'] = array(
			'type' => 'image',
			'list' => demopress()->find_builders( 'image', array( 'local' ) )
		);
		$this->builders['inline']   = array(
			'type' => 'image',
			'list' => demopress()->find_builders( 'image' )
		);
	}

	protected function generate_item( $type ) {
		$this->_item_post($type);
	}
}
