<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Base\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Posts extends Generator {
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
						EL::i( 'posts', 'type-' . $cpt, __( "Generate", "demopress" ), __( "Enable this option to generate the posts for this post type, and show options for the generator controls.", "demopress" ), Type::BOOLEAN, false )->args( array(
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
					EL::i( 'posts', $cpt . '-base-count', __( "Number of Posts", "demopress" ), '', Type::ABSINT, 5 )->args( array(
						'min' => 1
					) )
				)
			);

			$_settings = array(
				EL::i( 'posts', $cpt . '-builder-title', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'title', $this->builders['title']['list'] ) )->args( array(
					'data'          => array( 'switch' => 'demopress-builders-title-' . $cpt ),
					'wrapper_class' => 'demopress-builder-switch'
				) )
			);

			$_hidden = false;
			foreach ( $this->objects['title'] as $obj ) {
				$settings = $obj->settings( 'posts', $cpt, 'title', 'demopress-builders-title-' . $cpt, $_hidden );

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
				EL::i( 'posts', $cpt . '-builder-content', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'html', $this->builders['content']['list'] ) )->args( array(
					'data'          => array( 'switch' => 'demopress-builders-content-' . $cpt ),
					'wrapper_class' => 'demopress-builder-switch'
				) )
			);

			$_hidden = false;
			foreach ( $this->objects['content'] as $obj ) {
				$settings = $obj->settings( 'posts', $cpt, 'content', 'demopress-builders-content-' . $cpt, $_hidden );

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
				EL::i( 'posts', $cpt . '-base-published-from', __( "From", "demopress" ), '', Type::DATE, date( 'Y-m-d', time() - YEAR_IN_SECONDS ) ),
				EL::i( 'posts', $cpt . '-base-published-to', __( "To", "demopress" ), '', Type::DATE, date( 'Y-m-d' ) ),
				EL::i( 'posts', $cpt . '-base-published-author', __( "Author", "demopress" ), __( "Comma separated list of user ID's to use on random. If empty, plugin will choose random users from role author and up.", "demopress" ), Type::TEXT, '' )
			);

			$_sections[] = array(
				'label'    => __( "Published", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => $_settings
			);

			$_settings = array(
				EL::i( 'posts', $cpt . '-base-excerpt', __( "Status", "demopress" ), __( "Excerpt is optional.", "demopress" ), Type::SELECT, 'off' )->data( 'array', array(
					'on'  => __( "Enabled", "demopress" ),
					'off' => __( "Disabled", "demopress" )
				) )->args( array(
					'label'         => __( "Generate", "demopress" ),
					'wrapper_class' => 'demopress-builder-status'
				) ),
				EL::i( 'posts', $cpt . '-builder-excerpt', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'text', $this->builders['excerpt']['list'] ) )->args( array(
					'data'          => array( 'switch' => 'demopress-builders-excerpt-' . $cpt ),
					'wrapper_class' => 'demopress-builder-switch'
				) )
			);

			$_hidden = false;
			foreach ( $this->objects['excerpt'] as $obj ) {
				$settings = $obj->settings( 'posts', $cpt, 'excerpt', 'demopress-builders-excerpt-' . $cpt, $_hidden );

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

			$_settings = array(
				EL::i( 'posts', $cpt . '-base-featured', __( "Status", "demopress" ), __( "Featured image is optional. But, it has to be downloaded, it can't be a link to external image.", "demopress" ), Type::SELECT, 'on' )->data( 'array', array(
					'on'  => __( "Enabled", "demopress" ),
					'off' => __( "Disabled", "demopress" )
				) )->args( array(
					'label'         => __( "Generate", "demopress" ),
					'wrapper_class' => 'demopress-builder-status'
				) ),
				EL::i( 'posts', $cpt . '-builder-featured', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'image', $this->builders['featured']['list'] ) )->args( array(
					'data'          => array( 'switch' => 'demopress-builders-featured-' . $cpt ),
					'wrapper_class' => 'demopress-builder-switch'
				) )
			);

			$_hidden = false;
			foreach ( $this->objects['featured'] as $obj ) {
				$settings = $obj->settings( 'posts', $cpt, 'featured', 'demopress-builders-featured-' . $cpt, $_hidden );

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

			$_taxonomies = get_object_taxonomies( $cpt );

			foreach ( $_taxonomies as $tax ) {
				$taxonomy = get_taxonomy( $tax );
				$terms    = wp_count_terms( $tax, array( 'hide_empty' => false ) );

				if ( $terms == 0 ) {
					continue;
				}

				$_settings = array(
					EL::i( 'posts', $cpt . '-base-taxonomy-' . $tax . '-generate', __( "Status", "demopress" ), __( "Assigning terms is optional.", "demopress" ), Type::SELECT, 'on' )->data( 'array', array(
						'on'  => __( "Enabled", "demopress" ),
						'off' => __( "Disabled", "demopress" )
					) )->args( array(
						'label'         => __( "Generate", "demopress" ),
						'wrapper_class' => 'demopress-builder-status'
					) ),
					EL::i( 'posts', $cpt . '-base-taxonomy-' . $tax . '-assign', __( "Assign to posts", "demopress" ), __( "Percentage of random generated posts that will get terms assigned. Set to 100% to assign terms to all posts.", "demopress" ), Type::ABSINT, 100 )->args( array(
						'label_unit' => '%',
						'min'        => 0,
						'step'       => 5,
						'max'        => 100
					) ),
					EL::i( 'posts', $cpt . '-base-taxonomy-' . $tax . '-terms', __( "Terms to assign", "demopress" ), __( "Number of terms to assign inside the specified range, at random.", "demopress" ), Type::RANGE_ABSINT, '1=>3' )
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
						EL::i( 'posts', $cpt . '-base-toplevel', __( "Top level posts", "demopress" ), __( "Percentage of total posts to generate to be top level posts.", "demopress" ), Type::ABSINT, 70 )->args( array(
							'label_unit' => '%',
							'min'        => 0,
							'step'       => 5,
							'max'        => 100
						) )
					)
				);
			}

			$this->settings[ $cpt ] = array(
				'name'     => $post_type->label,
				'sections' => $_sections,
				'args'     => array( 'class' => 'demopress-type-settings-hidden' )
			);
		}
	}

	protected function generate_item( $type ) {

	}

	public function get_list_of_types() {
		$post_types = demopress_get_post_types();

		return array_keys( $post_types );
	}
}
