<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Base\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Terms extends Generator {
	public $name = 'terms';

	public function get_list_of_types( $return = 'objects' ) {
		$taxonomies = demopress_get_taxonomies();

		return $return == 'keys' ? array_keys( $taxonomies ) : $taxonomies;
	}

	protected function init_builders() {
		$this->builders['term']        = array(
			'type' => 'term',
			'list' => demopress()->find_builders( 'term' )
		);
		$this->builders['description'] = array(
			'type' => 'text',
			'list' => demopress()->find_builders( 'text' )
		);
	}

	protected function init_settings() {
		$taxonomies = $this->get_list_of_types();

		$this->settings = array();

		foreach ( $taxonomies as $tax => $taxonomy ) {
			$_sections = array(
				array(
					'label'    => '',
					'name'     => '',
					'class'    => '',
					'settings' => array(
						EL::i( $this->name, 'type-' . $tax, __( "Generate", "demopress" ), __( "Enable this option to generate the terms for this taxonomy, and show options for the generator controls.", "demopress" ), Type::BOOLEAN, false )->args( array(
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
					EL::i( $this->name, $tax . '-base-count', __( "Number of Terms", "demopress" ), '', Type::ABSINT, 5 )->args( array(
						'min' => 1
					) )
				)
			);

			$_settings = array(
				EL::i( $this->name, $tax . '-builder-term', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'term', $this->builders['term']['list'] ) )->args( array(
					'data'          => array( 'switch' => 'demopress-builders-term-' . $tax ),
					'wrapper_class' => 'demopress-builder-switch'
				) )
			);

			$_hidden = false;
			foreach ( $this->objects['term'] as $obj ) {
				$settings = $obj->settings( 'terms', $tax, 'term', 'demopress-builders-term-' . $tax, $_hidden );

				if ( ! empty( $settings ) ) {
					$_settings = array_merge( $_settings, $settings );
				}

				$_hidden = true;
			}

			$_sections[] = array(
				'label'    => __( "Name", "demopress" ),
				'name'     => '',
				'class'    => '',
				'settings' => $_settings
			);

			$_settings = array(
				EL::i( $this->name, $tax . '-base-description', __( "Status", "demopress" ), '', Type::SELECT, 'off' )->data( 'array', array(
					'on'  => __( "Enabled", "demopress" ),
					'off' => __( "Disabled", "demopress" )
				) )->args( array(
					'label'         => __( "Generate", "demopress" ),
					'wrapper_class' => 'demopress-builder-status'
				) ),
				EL::i( $this->name, $tax . '-builder-description', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'text', $this->builders['description']['list'] ) )->args( array(
					'data'          => array( 'switch' => 'demopress-builders-description-' . $tax ),
					'wrapper_class' => 'demopress-builder-switch'
				) )
			);

			$_hidden = false;
			foreach ( $this->objects['description'] as $obj ) {
				$settings = $obj->settings( 'terms', $tax, 'description', 'demopress-builders-description-' . $tax, $_hidden );

				if ( ! empty( $settings ) ) {
					$_settings = array_merge( $_settings, $settings );
				}

				$_hidden = true;
			}

			$_sections[] = array(
				'label'    => __( "Description", "demopress" ),
				'name'     => '',
				'class'    => 'demopress-type-section-hidden',
				'settings' => $_settings
			);

			if ( is_taxonomy_hierarchical( $tax ) ) {
				$_sections[] = array(
					'label'    => __( "Hierarchy", "demopress" ),
					'name'     => '',
					'class'    => '',
					'settings' => array(
						EL::i( $this->name, $tax . '-base-parent', __( "Parent", "demopress" ), __( "If you select the parent, all generated terms in this task will be children of the selected parent.", "demopress" ), Type::DROPDOWN_CATEGORIES, 0 ),
						EL::i( $this->name, $tax . '-base-toplevel', __( "Top level terms", "demopress" ), __( "Percentage of total terms to generate to be top level terms.", "demopress" ), Type::ABSINT, 50 )->args( array(
							'label_unit' => '%',
							'min'        => 0,
							'step'       => 5,
							'max'        => 100
						) )
					)
				);
			}

			$this->settings[ $tax ] = array(
				'name'     => $taxonomy->label,
				'sections' => $_sections,
				'args'     => array( 'class' => 'demopress-type-settings-hidden' )
			);
		}
	}

	protected function generate_item( $type ) {
		$parent = $this->get_from_base( $type, 'parent', false, 0 );

		$this->_cache_terms( $type, $parent );

		$term = array(
			'name'        => $this->get_from_builder( $type, 'term' ),
			'parent'      => 0,
			'description' => ''
		);

		$term['slug'] = sanitize_title( $term['name'] );

		if ( $this->get_from_base( $type, 'description' ) == 'on' ) {
			$term['description'] = $this->get_from_builder( $type, 'description' );
		}

		if ( is_taxonomy_hierarchical( $type ) && $this->get_from_base( $type, 'toplevel' ) < 100 ) {
			$term['parent'] = $parent;

			$toplevel = ceil( $this->get_from_base( $type, 'toplevel' ) * ( $this->get_from_base( $type, 'count' ) / 100 ) );

			if ( $toplevel >= $this->current_item() + 1 && ! empty( $this->_terms_cache[ $type ] ) ) {
				$term['parent'] = $this->_terms_cache[ $type ][ array_rand( $this->_terms_cache[ $type ] ) ];
			}
		}

		$the_term = wp_insert_term( $term['name'], $type, array(
			'description' => $term['description'],
			'slug'        => $term['slug'],
			'parent'      => $term['parent']
		) );

		if ( ! is_wp_error( $the_term ) ) {
			$term_id = $the_term['term_id'];

			$this->_terms_cache[ $type ][] = $term_id;

			update_term_meta( $term_id, '_demopress_generated_content', '1' );

			$this->add_log_entry(
				sprintf( __( "Added Term - ID: %s, Term: '%s'", "demopress" ),
					$term_id, $term['name'] ) );

			shuffle( $this->_terms_cache[ $type ] );

			$this->item_done();
		}
	}
}
