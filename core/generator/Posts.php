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

		if ( $this->get_from_base( $type, 'excerpt' ) == 'on' ) {
			$post['post_excerpt'] = $this->get_from_builder( $type, 'excerpt' );
		}

		$terms = $this->_get_terms( $type );

		foreach ( $terms as $tax => $t ) {
			if ( $tax == 'category' ) {
				$post['post_category'] = $t;
				unset( $terms[ $tax ] );
			} else if ( $tax == 'post_tag' ) {
				$post['tags_input'] = $t;
				unset( $terms[ $tax ] );
			}
		}

		if ( ! empty( $terms ) ) {
			$post['tax_input'] = $terms;
		}

		if ( is_post_type_hierarchical( $type ) && $this->get_from_base( $type, 'toplevel' ) < 100 ) {
			$post['post_parent'] = $parent;

			$toplevel = ceil( $this->get_from_base( $type, 'toplevel' ) * ( $this->get_from_base( $type, 'count' ) / 100 ) );

			if ( $toplevel >= $this->current_item() + 1 && ! empty( $this->_posts_cache[ $type ] ) ) {
				$post['post_parent'] = $this->_posts_cache[ $type ][ array_rand( $this->_posts_cache[ $type ] ) ];
			}
		}

		$post_id = wp_insert_post( $post );

		if ( ! is_wp_error( $post_id ) ) {
			$this->_posts_cache[ $type ][] = $post_id;

			update_post_meta( $post_id, '_demopress_generated_content', '1' );

			$this->add_log_entry(
				sprintf( __( "Added Post - ID: %s, Name: '%s'", "demopress" ),
					$post_id, $post['post_title'] ) );

			if ( $this->get_from_base( $type, 'featured' ) == 'on' ) {
				$image = $this->get_from_builder( $type, 'featured' );

				if ( ! is_wp_error( $image ) && is_array( $image ) && ! empty( $image ) ) {
					if ( $this->get_builder_scope( $type, 'featured' ) == 'remote' ) {
						$image = $this->_attach_featured_image_remote( $image, $post_id );
					} else {
						$image = $this->_attach_featured_image_local( $image, $post_id );
					}
				}

				if ( is_wp_error( $image ) ) {
					$this->add_log_entry( __( "Failed attaching the image.", "demopress" ) );
					$this->add_log_entry( $image->get_error_message() );
				}
			}

			shuffle( $this->_posts_cache[ $type ] );
		} else {
			$this->add_log_entry(
				sprintf( __( "Failed creating the post. Name: '%s'", "demopress" ),
					$post['post_title'] ) );
		}

		$this->item_done();
	}
}
