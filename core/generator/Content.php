<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use DateTime;
use Dev4Press\Plugin\DemoPress\Base\Generator;
use Dev4Press\v35\Core\Options\Element as EL;
use Dev4Press\v35\Core\Options\Type;
use Dev4Press\v35\WordPress\Media\ToLibrary\LocalImage;
use Dev4Press\v35\WordPress\Media\ToLibrary\RemoteImage;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Content extends Generator {
	public $attached_images = true;

	public function get_attached_images_count( $type ) {
		return demopress_db()->get_attachments_for_cleanup( $type, true );
	}

	public function run_attached_images_cleanup( $type ) {
		$ids = demopress_db()->get_attachments_for_cleanup( $type );

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $id ) {
				wp_delete_attachment( $id, true );
			}
		}

		return count( $ids );
	}

	public function get_cleanup_count( $type = '' ) {
		return demopress_db()->get_posts_for_cleanup( $type, true );
	}

	public function run_cleanup( $type ) {
		$ids = demopress_db()->get_posts_for_cleanup( $type );

		if ( ! empty( $ids ) ) {
			demopress_db()->run_posts_cleanup( $ids );
		}

		return count( $ids );
	}

	protected function init_settings() {
		$post_types = $this->get_list_of_types();

		foreach ( $post_types as $cpt => $post_type ) {
			$_sections = array(
				array(
					'key'      => 'status',
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
				'key'      => 'basic',
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
				EL::i( $this->name, $cpt . '-builder-title', __( "Generate with", "demopress" ), '', Type::SELECT )->data( 'array', demopress()->list_builders( 'title', $this->builders['title']['list'] ) )->args( array(
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
				'key'      => 'title',
				'label'    => __( "Title", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => $_settings
			);

			$_settings = array(
				EL::i( $this->name, $cpt . '-builder-content', __( "Generate with", "demopress" ), '', Type::SELECT )->data( 'array', demopress()->list_builders( 'html', $this->builders['content']['list'] ) )->args( array(
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
				'key'      => 'content',
				'label'    => __( "Content", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => $_settings
			);

			$_settings = array(
				EL::i( $this->name, $cpt . '-base-published-from', __( "From", "demopress" ), '', Type::DATE, date( 'Y-m-d', time() - YEAR_IN_SECONDS ) ),
				EL::i( $this->name, $cpt . '-base-published-to', __( "To", "demopress" ), '', Type::DATE, date( 'Y-m-d' ) ),
				EL::i( $this->name, $cpt . '-base-published-author', __( "Author", "demopress" ), __( "Comma separated list of user ID's to use on random. If empty, plugin will choose random users from role author and up.", "demopress" ), Type::TEXT )
			);

			$_sections[] = array(
				'key'      => 'published',
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
					EL::i( $this->name, $cpt . '-builder-excerpt', __( "Generate with", "demopress" ), '', Type::SELECT )->data( 'array', demopress()->list_builders( 'text', $this->builders['excerpt']['list'] ) )->args( array(
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
					'key'      => 'excerpt',
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
					EL::i( $this->name, $cpt . '-builder-featured', __( "Generate with", "demopress" ), '', Type::SELECT )->data( 'array', demopress()->list_builders( 'image', $this->builders['featured']['list'] ) )->args( array(
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
					'key'      => 'featured-image',
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
					'key'      => 'taxonomy-' . $tax,
					'label'    => sprintf( __( "Assign terms for '%s'", "demopress" ), $taxonomy->labels->name ),
					'name'     => '',
					'class'    => '',
					'settings' => $_settings
				);
			}

			if ( is_post_type_hierarchical( $cpt ) ) {
				$_sections[] = array(
					'key'      => 'hierarchy',
					'label'    => __( "Hierarchy", "demopress" ),
					'name'     => '',
					'class'    => '',
					'settings' => array(
						EL::i( $this->name, $cpt . '-base-parent', __( "Parent", "demopress" ), __( "If you select the parent, all generated posts in this task will be children of the selected parent.", "demopress" ), Type::DROPDOWN_PAGES, 0 )->args( array( 'post_type' => $cpt ) ),
						EL::i( $this->name, $cpt . '-base-toplevel', __( "Top level posts", "demopress" ), __( "Percentage of total posts to generate to be top level posts. Of the parent is selected, this option will assume that top level is the children level to selected parent.", "demopress" ), Type::ABSINT, 70 )->args( array(
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
				'sections' => $this->pre_sections( $_sections, $cpt ),
				'args'     => array( 'class' => 'demopress-type-settings-hidden' )
			);
		}
	}

	protected function _get_terms( $type ) {
		$terms      = array();
		$taxonomies = $this->get_from_base( $type, 'taxonomy', false, array() );

		if ( ! empty( $taxonomies ) ) {
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

							if ( $range[0] <= $range[1] ) {
								$count = mt_rand( $range[0], $range[1] );

								if ( $count > 0 ) {
									if ( $count >= count( $this->_terms_cache[ $tax ] ) ) {
										$pick = array_keys( $this->_terms_cache[ $tax ] );
									} else {
										$pick = (array) array_rand( $this->_terms_cache[ $tax ], $count );
									}

									foreach ( $pick as $key ) {
										$terms[ $tax ][] = absint( $this->_terms_cache[ $tax ][ $key ] );
									}
								}
							}
						}
					}
				}
			}
		}

		return $terms;
	}

	protected function _get_author( $type, $roles = array() ) {
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
			$roles = empty( $roles ) ? array( 'administrator', 'editor', 'author' ) : array();
			$this->_cache_users( $roles );

			$authors = $this->_users_cache;
		}

		$key = array_rand( $authors );

		return $authors[ $key ];
	}

	protected function _get_publish_date( $type ) {
		$range = $this->get_from_base( $type, 'published' );

		$from_date = DateTime::createFromFormat( '!Y-m-d', $range['from'] );
		$to_date   = DateTime::createFromFormat( '!Y-m-d', $range['to'] );

		$random      = mt_rand( $from_date->getTimestamp(), $to_date->getTimestamp() + DAY_IN_SECONDS - 1 );
		$random_date = new DateTime();
		$random_date->setTimestamp( $random );

		return $random_date->format( 'Y-m-d H:i:s' );
	}

	protected function _attach_featured_image_remote( $image, $post_id = 1 ) {
		$url  = $image['url'];
		$data = $image['data'];

		$uploader      = new RemoteImage( $url, $data );
		$attachment_id = $uploader->download( $post_id, true );

		if ( ! is_wp_error( $attachment_id ) ) {
			update_post_meta( $attachment_id, '_demopress_generated_content', '1' );
		}

		return $attachment_id;
	}

	protected function _attach_featured_image_local( $image, $post_id = 1 ) {
		$path = $image['path'];
		$data = $image['data'];

		if ( file_exists( $path ) ) {
			$uploader      = new LocalImage( $path, $data );
			$attachment_id = $uploader->upload( $post_id, true );

			if ( ! is_wp_error( $attachment_id ) ) {
				update_post_meta( $attachment_id, '_demopress_generated_content', '1' );
			}

			if ( file_exists( $path ) ) {
				unlink( $path );
			}

			return $attachment_id;
		} else {
			return new WP_Error( 'image_missing', __( "Image is not found at temp location.", "demopress" ) );
		}
	}

	protected function _item_post( $type ) {
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

		return ! is_wp_error( $post_id ) ? $post_id : false;
	}
}