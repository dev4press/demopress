<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bbPress extends Content {
	public $name = 'bbpress';

	public function get_list_of_types( $return = 'objects' ) {
		$post_types = demopress_get_bbpress_post_types();

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
	}

	protected function generate_item( $type ) {
		switch ($type) {
			case bbp_get_forum_post_type():
				$this->_item_forum($type);
				break;
			case bbp_get_topic_post_type():
				$this->_item_topic($type);
				break;
			case bbp_get_reply_post_type():
				$this->_item_reply($type);
				break;
		}
	}

	private function _item_forum($type) {
		$parent = $this->get_from_base( $type, 'parent', false, 0 );

		$this->_cache_posts( $type, $parent );

		$post = array(
			'post_title'   => $this->get_from_builder( $type, 'title' ),
			'post_content' => $this->get_from_builder( $type, 'content' ),
			'post_date'    => $this->_get_publish_date( $type ),
			'post_author'  => $this->_get_author( $type ),
			'post_status'  => 'publish',
			'post_type'    => $type
		);

		if ( $this->get_from_base( $type, 'toplevel' ) < 100 ) {
			$post['post_parent'] = $parent;

			$toplevel = ceil( $this->get_from_base( $type, 'toplevel' ) * ( $this->get_from_base( $type, 'count' ) / 100 ) );

			if ( $toplevel >= $this->current_item() + 1 && ! empty( $this->_posts_cache[ $type ] ) ) {
				$post['post_parent'] = $this->_posts_cache[ $type ][ array_rand( $this->_posts_cache[ $type ] ) ];
			}
		}

		$post_id = bbp_insert_forum( $post );

		if ( ! is_wp_error( $post_id ) && $post_id !== false ) {
			$this->_posts_cache[ $type ][] = $post_id;

			update_post_meta( $post_id, '_demopress_generated_content', '1' );

			$this->add_log_entry(
				sprintf( __( "Added Post - ID: %s, Name: '%s'", "demopress" ),
					$post_id, $post['post_title'] ) );

			shuffle( $this->_posts_cache[ $type ] );
		} else {
			$this->add_log_entry(
				sprintf( __( "Failed creating the post. Name: '%s'", "demopress" ),
					$post['post_title'] ) );
		}

		$this->item_done();
	}

	private function _item_topic($type) {

	}

	private function _item_reply($type) {

	}
}
