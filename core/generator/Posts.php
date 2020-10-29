<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use DateTime;
use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Base\Generator;
use Dev4Press\WordPress\Media\ToLibrary\LocalImage;
use WP_User_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Posts extends Generator {
	private $_terms_cache = array();
	private $_posts_cache = array();
	private $_users_cache = array();

	public $name = 'posts';

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

	protected function init_settings() {
		$post_types = demopress_get_post_types();

		foreach ( $post_types as $cpt => $post_type ) {
			$_sections = array(
				array(
					'label'    => '',
					'name'     => '',
					'class'    => '',
					'settings' => array(
						EL::i( $this->name, 'type-' . $cpt, __( "Generate", "demopress" ), __( "Enable this option to generate the posts for this post type, and show options for the generator controls.", "demopress" ), Type::BOOLEAN, false )->args( array(
							'class' => 'demopress-type-settings-ctrl'
						) )
					)
				)
			);

			$_sections[] = array(
				'label'    => __( "Basic", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => array(
					EL::i( $this->name, $cpt . '-base-count', __( "Number of Posts", "demopress" ), '', Type::ABSINT, 5 )->args( array(
						'min' => 1
					) )
				)
			);

			$_settings = array(
				EL::i( $this->name, $cpt . '-builder-title', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'title', $this->builders['title']['list'] ) )->args( array(
					'data'          => array( 'switch' => 'demopress-builders-title-' . $cpt ),
					'wrapper_class' => 'demopress-builder-switch'
				) )
			);

			$_hidden = false;
			foreach ( $this->objects['title'] as $obj ) {
				$settings = $obj->settings( $this->name, $cpt, 'title', 'demopress-builders-title-' . $cpt, $_hidden );

				if ( ! empty( $settings ) ) {
					$_settings = array_merge( $_settings, $settings );
				}

				$_hidden = true;
			}

			$_sections[] = array(
				'label'    => __( "Title", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => $_settings
			);

			$_settings = array(
				EL::i( $this->name, $cpt . '-builder-content', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'html', $this->builders['content']['list'] ) )->args( array(
					'data'          => array( 'switch' => 'demopress-builders-content-' . $cpt ),
					'wrapper_class' => 'demopress-builder-switch'
				) )
			);

			$_hidden = false;
			foreach ( $this->objects['content'] as $obj ) {
				$settings = $obj->settings( $this->name, $cpt, 'content', 'demopress-builders-content-' . $cpt, $_hidden );

				if ( ! empty( $settings ) ) {
					$_settings = array_merge( $_settings, $settings );
				}

				$_hidden = true;
			}

			$_sections[] = array(
				'label'    => __( "Content", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => $_settings
			);

			$_settings = array(
				EL::i( $this->name, $cpt . '-base-published-from', __( "From", "demopress" ), '', Type::DATE, date( 'Y-m-d', time() - YEAR_IN_SECONDS ) ),
				EL::i( $this->name, $cpt . '-base-published-to', __( "To", "demopress" ), '', Type::DATE, date( 'Y-m-d' ) ),
				EL::i( $this->name, $cpt . '-base-published-author', __( "Author", "demopress" ), __( "Comma separated list of user ID's to use on random. If empty, plugin will choose random users from role author and up.", "demopress" ), Type::TEXT, '' )
			);

			$_sections[] = array(
				'label'    => __( "Published", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => $_settings
			);

			if ( post_type_supports( $cpt, 'excerpt' ) ) {
				$_settings = array(
					EL::i( $this->name, $cpt . '-base-excerpt', __( "Status", "demopress" ), __( "Excerpt is optional.", "demopress" ), Type::SELECT, 'off' )->data( 'array', array(
						'on'  => __( "Enabled", "demopress" ),
						'off' => __( "Disabled", "demopress" )
					) )->args( array(
						'label'         => __( "Generate", "demopress" ),
						'wrapper_class' => 'demopress-builder-status'
					) ),
					EL::i( $this->name, $cpt . '-builder-excerpt', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'text', $this->builders['excerpt']['list'] ) )->args( array(
						'data'          => array( 'switch' => 'demopress-builders-excerpt-' . $cpt ),
						'wrapper_class' => 'demopress-builder-switch'
					) )
				);

				$_hidden = false;
				foreach ( $this->objects['excerpt'] as $obj ) {
					$settings = $obj->settings( $this->name, $cpt, 'excerpt', 'demopress-builders-excerpt-' . $cpt, $_hidden );

					if ( ! empty( $settings ) ) {
						$_settings = array_merge( $_settings, $settings );
					}

					$_hidden = true;
				}

				$_sections[] = array(
					'label'    => __( "Excerpt", "demopress" ),
					'name'     => '',
					'class'    => 'demopress-type-section-hidden',
					'settings' => $_settings
				);
			} else {
				$_sections[0]['settings'][] = EL::i( $this->name, $cpt . '-base-excerpt', '', '', Type::HIDDEN, 'off' );
			}

			if ( post_type_supports( $cpt, 'thumbnail' ) ) {
				$_settings = array(
					EL::i( $this->name, $cpt . '-base-featured', __( "Status", "demopress" ), __( "Featured image is optional. But, it has to be downloaded, it can't be a link to external image.", "demopress" ), Type::SELECT, 'on' )->data( 'array', array(
						'on'  => __( "Enabled", "demopress" ),
						'off' => __( "Disabled", "demopress" )
					) )->args( array(
						'label'         => __( "Generate", "demopress" ),
						'wrapper_class' => 'demopress-builder-status'
					) ),
					EL::i( $this->name, $cpt . '-builder-featured', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'image', $this->builders['featured']['list'] ) )->args( array(
						'data'          => array( 'switch' => 'demopress-builders-featured-' . $cpt ),
						'wrapper_class' => 'demopress-builder-switch'
					) )
				);

				$_hidden = false;
				foreach ( $this->objects['featured'] as $obj ) {
					$settings = $obj->settings( $this->name, $cpt, 'featured', 'demopress-builders-featured-' . $cpt, $_hidden );

					if ( ! empty( $settings ) ) {
						$_settings = array_merge( $_settings, $settings );
					}

					$_hidden = true;
				}

				$_sections[] = array(
					'label'    => __( "Featured Image", "demopress" ),
					'name'     => '',
					'class'    => '',
					'settings' => $_settings
				);
			} else {
				$_sections[0]['settings'][] = EL::i( $this->name, $cpt . '-base-featured', '', '', Type::HIDDEN, 'off' );
			}

			$_taxonomies = get_object_taxonomies( $cpt );

			foreach ( $_taxonomies as $tax ) {
				$taxonomy = get_taxonomy( $tax );
				$terms    = wp_count_terms( $tax, array( 'hide_empty' => false ) );

				if ( $terms == 0 ) {
					continue;
				}

				$_settings = array(
					EL::i( $this->name, $cpt . '-base-taxonomy-' . $tax . '-generate', __( "Status", "demopress" ), __( "Assigning terms is optional.", "demopress" ), Type::SELECT, 'on' )->data( 'array', array(
						'on'  => __( "Enabled", "demopress" ),
						'off' => __( "Disabled", "demopress" )
					) )->args( array(
						'label'         => __( "Generate", "demopress" ),
						'wrapper_class' => 'demopress-builder-status'
					) ),
					EL::i( $this->name, $cpt . '-base-taxonomy-' . $tax . '-assign', __( "Assign to posts", "demopress" ), __( "Percentage of random generated posts that will get terms assigned. Set to 100% to assign terms to all posts.", "demopress" ), Type::ABSINT, 100 )->args( array(
						'label_unit' => '%',
						'min'        => 0,
						'step'       => 5,
						'max'        => 100
					) ),
					EL::i( $this->name, $cpt . '-base-taxonomy-' . $tax . '-terms', __( "Terms to assign", "demopress" ), __( "Number of terms to assign inside the specified range, at random.", "demopress" ), Type::RANGE_ABSINT, '1=>3' )
				);

				$_sections[] = array(
					'label'    => sprintf( __( "Assign terms for '%s'", "demopress" ), $taxonomy->labels->name ),
					'name'     => '',
					'class'    => '',
					'settings' => $_settings
				);
			}

			if ( is_post_type_hierarchical( $cpt ) ) {
				$_sections[] = array(
					'label'    => __( "Hierarchy", "demopress" ),
					'name'     => '',
					'class'    => '',
					'settings' => array(
						EL::i( $this->name, $cpt . '-base-toplevel', __( "Top level posts", "demopress" ), __( "Percentage of total posts to generate to be top level posts.", "demopress" ), Type::ABSINT, 70 )->args( array(
							'label_unit' => '%',
							'min'        => 0,
							'step'       => 5,
							'max'        => 100
						) )
					)
				);
			} else {
				$_sections[0]['settings'][] = EL::i( $this->name, $cpt . '-base-toplevel', '', '', Type::HIDDEN, 100 );
			}

			$this->settings[ $cpt ] = array(
				'name'     => $post_type->label,
				'sections' => $_sections,
				'args'     => array( 'class' => 'demopress-type-settings-hidden' )
			);
		}
	}

	protected function generate_item( $type ) {
		$this->_cache_posts( $type );

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

		foreach ($terms as $tax => $t) {
			if ($tax == 'category') {
				$post['post_category'] = $t;
				unset($terms[$tax]);
			} else if ($tax == 'post_tag') {
				$post['tags_input'] = $t;
				unset($terms[$tax]);
			}
		}

		if (!empty($terms)) {
			$post['tax_input'] = '';
		}

		if ( is_post_type_hierarchical( $type ) && $this->get_from_base( $type, 'toplevel' ) < 100 ) {
			$toplevel = ceil( $this->get_from_base( $type, 'toplevel' ) * ( $this->get_from_base( $type, 'count' ) / 100 ) );

			if ( $toplevel >= $this->current_item() + 1 && ! empty( $this->_posts_cache[ $type ] ) ) {
				$item                = $this->_posts_cache[ $type ][ array_rand( $this->_posts_cache[ $type ] ) ];
				$post['post_parent'] = $item->post_id;
			}
		}

		$post_id = wp_insert_post( $post );

		if ( ! is_wp_error( $post_id ) ) {
			$this->_posts_cache[ $type ][] = (object) array( 'post_id' => $post_id );

			update_post_meta( $post_id, '_demopress_generated_content', '1' );

			$this->add_log_entry(
				sprintf( __( "Added Post - ID: %s, Name: '%s'", "demopress" ),
					$post_id, $post['post_title'] ) );

			if ( $this->get_from_base( $type, 'featured' ) == 'on' ) {
				$image = $this->get_from_builder( $type, 'featured' );

				if ( ! is_wp_error( $image ) && is_string( $image ) && file_exists( $image ) ) {
					$image = $this->_attach_featured_image( $image, $post_id );
				}

				if ( is_wp_error( $image ) ) {
					$this->add_log_entry( __( "Failed attaching the image.", "demopress" ) );
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

	private function _get_terms( $type ) {
		$terms      = array();
		$taxonomies = $this->get_from_base( $type, 'taxonomy' );

		foreach ( $taxonomies as $tax => $settings ) {
			if ( $settings['generate'] == 'on' ) {
				$this->_cache_terms( $tax );

				if ( ! empty( $this->_terms_cache[ $tax ] ) ) {
					$assign = true;
					if ( $settings['assign'] < 100 ) {
						$assign = $settings['assign'] > mt_rand( 1, 100 );
					}

					if ( $assign ) {
						$range = explode( '=>', $settings['terms'] );
						$range = array_map( 'absint', $range );

						$count = mt_rand( $range[0], $range[1] );

						if ( $count >= count( $this->_terms_cache[ $tax ] ) ) {
							$pick = array_keys( $this->_terms_cache[ $tax ] );
						} else {
							$pick = (array) array_rand( $this->_terms_cache[ $tax ], $count );
						}

						foreach ( $pick as $key ) {
							$id = $this->_terms_cache[ $tax ][ $key ]->term_id;
							$terms[ $tax ][] = absint($id);
						}
					}
				}
			}
		}

		return $terms;
	}

	private function _get_author( $type ) {
		$authors = $this->get_from_base( $type, 'published', 'author' );

		if ( ! empty( $authors ) ) {
			$authors = explode( ',', $authors );
			$authors = array_map( 'trim', $authors );
			$authors = array_map( 'absint', $authors );
			$authors = array_unique( $authors );
			$authors = array_filter( $authors );
		} else {
			$authors = array();
		}

		if ( empty( $authors ) ) {
			$this->_cache_users();

			$authors = $this->_users_cache;
		}

		$key = array_rand( $authors );

		return $authors[ $key ];
	}

	private function _get_publish_date( $type ) {
		$range = $this->get_from_base( $type, 'published' );

		$from_date = DateTime::createFromFormat( '!Y-m-d', $range['from'] );
		$to_date   = DateTime::createFromFormat( '!Y-m-d', $range['to'] );

		$random      = mt_rand( $from_date->getTimestamp(), $to_date->getTimestamp() + DAY_IN_SECONDS - 1 );
		$random_date = new DateTime();
		$random_date->setTimestamp( $random );

		return $random_date->format( 'Y-m-d H:i:s' );
	}

	private function _attach_featured_image( $image, $post_id = 1 ) {
		$uploader      = new LocalImage( $image );
		$attachment_id = $uploader->upload( $post_id, true );

		if ( file_exists( $image ) ) {
			unlink( $image );
		}

		return $attachment_id;
	}

	private function _cache_users() {
		if ( empty( $this->_users_cache ) ) {
			$query = new WP_User_Query( array(
				'role__in' => array( 'administrator', 'editor', 'author' ),
				'fields'   => 'ID',
				'number'   => - 1
			) );

			$this->_users_cache = $query->get_results();
		}
	}

	private function _cache_posts( $type ) {
		if ( empty( $this->_posts_cache[ $type ] ) ) {
			$this->_posts_cache[ $type ] = demopress_db()->get_posts_for_post_type( $type );
		}
	}

	private function _cache_terms( $tax ) {
		if ( empty( $this->_terms_cache[ $tax ] ) ) {
			$this->_terms_cache[ $tax ] = demopress_db()->get_terms_for_taxonomy( $tax );
		}
	}

	public function get_list_of_types() {
		$post_types = demopress_get_post_types();

		return array_keys( $post_types );
	}
}
