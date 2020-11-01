<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bbPress extends Content {
	public $name = 'bbpress';
	public $_list_forums = array();

	public function get_list_of_types( $return = 'objects' ) {
		$post_types = demopress_get_bbpress_post_types();

		return $return == 'keys' ? array_keys( $post_types ) : $post_types;
	}

	protected function init_builders() {
		add_filter( 'demopress_data_text_lorem_ipsum_block_supported', '__return_false' );

		$this->builders['title']   = array(
			'type' => 'title',
			'list' => demopress()->find_builders( 'title' )
		);
		$this->builders['content'] = array(
			'type' => 'html',
			'list' => demopress()->find_builders( 'html' )
		);
	}

	protected function pre_sections( $sections, $type ) {
		if ( $type == bbp_get_reply_post_type() ) {
			$_settings = array(
				EL::i( $this->name, $type . '-base-title', __( "Status", "demopress" ), __( "Title is not normally used for replies. You can still generate it, if you want.", "demopress" ), Type::SELECT, 'off' )->data( 'array', array(
					'on'  => __( "Enabled", "demopress" ),
					'off' => __( "Disabled", "demopress" )
				) )->args( array(
					'label'         => __( "Generate", "demopress" ),
					'wrapper_class' => 'demopress-builder-status'
				) ),
				EL::i( $this->name, $type . '-builder-title', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'title', $this->builders['title']['list'] ) )->args( array(
					'data'          => array( 'switch' => 'demopress-builders-title-' . $type ),
					'wrapper_class' => 'demopress-builder-switch'
				) )
			);

			$_hidden = false;
			foreach ( $this->objects['title'] as $obj ) {
				$settings = $obj->settings( $this->name, $type, 'title', 'demopress-builders-title-' . $type, $_hidden );

				if ( ! empty( $settings ) ) {
					$_settings = array_merge( $_settings, $settings );
				}

				$_hidden = true;
			}

			$_title = array(
				'key'      => 'title',
				'label'    => __( "Title", "demopress" ),
				'name'     => '',
				'class'    => 'demopress-type-section-hidden',
				'settings' => $_settings
			);

			foreach ( $sections as $id => $section ) {
				if ( $section['key'] == 'title' ) {
					$sections[ $id ] = $_title;
					break;
				}
			}

			$_published = array(
				'key'      => 'published',
				'label'    => __( "Published", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => array(
					EL::i( $this->name, $type . '-base-published-author', __( "Author", "demopress" ), __( "Comma separated list of user ID's to use on random. If empty, plugin will choose random users from role author and up.", "demopress" ), Type::TEXT, '' )
				)
			);

			foreach ( $sections as $id => $section ) {
				if ( $section['key'] == 'published' ) {
					$sections[ $id ] = $_published;
					break;
				}
			}
		}

		if ( $type == bbp_get_topic_post_type() || $type == bbp_get_reply_post_type() ) {
			$_info = $type == bbp_get_topic_post_type()
				?
				__( "Topics can be added to any of the existing forums at random, or you can select which forums to limit the process to.", "demopress" )
				:
				__( "Replies can be added to topics of any of the existing forums at random, or you can select which forums to limit the process to.", "demopress" );

			$sections[] = array(
				'label'    => __( "Add to Forums", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => array(
					EL::i( $this->name, $type . '-base-forum-method', __( "Method", "demopress" ), $_info, Type::SELECT, 'any' )->data( 'array', array(
						'any' => __( "Any of the exisiting forums", "demopress" ),
						'sel' => __( "Selected forums only", "demopress" )
					) )->args( array(
						'data'          => array( 'switch' => 'demopress-builders-forum-method-' . $type ),
						'wrapper_class' => 'demopress-builder-switch'
					) ),
					EL::i( $this->name, $type . '-base-forum-list', __( "Selected forums", "demopress" ), '', Type::CHECKBOXES_HIERARCHY, array() )->data( 'array',
						demopress_get_bbpress_forums_list()
					)->args( array(
						'wrapper_class' => $this->el_wrapper_class( 'demopress-builders-forum-method-' . $type, 'sel', true )
					) )
				)
			);
		}

		return $sections;
	}

	protected function generate_item( $type ) {
		switch ( $type ) {
			case bbp_get_forum_post_type():
				$this->_item_forum( $type );
				break;
			case bbp_get_topic_post_type():
				$this->_item_topic( $type );
				break;
			case bbp_get_reply_post_type():
				$this->_item_reply( $type );
				break;
		}
	}

	private function _item_forum( $type ) {
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

	private function _item_topic( $type ) {
		$this->_cache_forums( $type );

		if ( ! empty( $this->_list_forums ) ) {
			$forum_key = array_rand( $this->_list_forums );
			$forum_id  = $this->_list_forums[ $forum_key ];

			$post = array(
				'post_title'   => $this->get_from_builder( $type, 'title' ),
				'post_content' => $this->get_from_builder( $type, 'content' ),
				'post_date'    => $this->_get_publish_date( $type ),
				'post_author'  => $this->_get_author( $type ),
				'post_status'  => 'publish',
				'post_type'    => $type,
				'post_parent'  => $forum_id
			);

			$post_id = bbp_insert_topic( $post, array(
				'forum_id' => $forum_id
			) );

			if ( ! is_wp_error( $post_id ) && $post_id !== false ) {
				update_post_meta( $post_id, '_demopress_generated_content', '1' );

				$this->add_log_entry(
					sprintf( __( "Added Post - ID: %s, Name: '%s'", "demopress" ),
						$post_id, $post['post_title'] ) );
			} else {
				$this->add_log_entry(
					sprintf( __( "Failed creating the post. Name: '%s'", "demopress" ),
						$post['post_title'] ) );
			}
		} else {
			$this->add_log_entry( __( "No forums found.", "demopress" ) );
		}

		$this->item_done();
	}

	private function _item_reply( $type ) {
		$this->_cache_forums( $type );

		if ( ! empty( $this->_list_forums ) ) {
			$post = array(
				'post_title'   => $this->get_from_builder( $type, 'title' ),
				'post_content' => $this->get_from_builder( $type, 'content' ),
				'post_date'    => $this->_get_publish_date( $type ),
				'post_author'  => $this->_get_author( $type ),
				'post_status'  => 'publish',
				'post_type'    => $type
			);
		} else {
			$this->add_log_entry( __( "No forums found.", "demopress" ) );
		}

		$this->item_done();
	}

	private function _cache_forums( $type ) {
		$what = $this->get_from_base( $type, 'forum', 'method' );

		if ( $what == 'any' ) {
			$raw = demopress_db()->get_posts_for_post_type( bbp_get_forum_post_type() );

			$this->_list_forums = wp_list_pluck( $raw, 'ID' );
		} else {
			$this->_list_forums = d4p_clean_ids_list( $this->get_from_base( $type, 'forum', 'list' ) );
		}
	}

	private function _cache_topics() {

	}

	protected function generate_thread_finished( $type ) {
		require_once( bbpress()->includes_dir . 'admin/tools/repair.php' );

		if ( $type == bbp_get_topic_post_type() ) {
			bbp_admin_repair_forum_topic_count();
		}

		if ( $type == bbp_get_reply_post_type() ) {
			bbp_admin_repair_forum_reply_count();
		}
	}

	protected function el_wrapper_class( $class, $name, $hidden = false ) {
		return $class . '-switch ' . $class . '-data-' . $name . ( $hidden ? ' demopress-is-hidden' : '' );
	}
}
