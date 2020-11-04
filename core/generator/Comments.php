<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Base\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Comments extends Generator {
	public $name = 'comments';

	private $_list_posts = array();
	private $_list_comments = array();

	public function get_cleanup_types() {
		$list = array();

		$comment_types = $this->get_list_of_types();
		$post_types    = demopress_get_post_types();

		foreach ( $post_types as $cpt => $post_type ) {
			foreach ( $comment_types as $cmm => $comment_type ) {
				if ( demopress_post_type_support_comment_type( $cpt, $cmm ) ) {
					$list[ $cpt . '::' . $cmm ] = sprintf( __( "%s for %s", "demopress" ), $comment_type->label, $post_type->label );
				}
			}
		}

		return $list;
	}

	public function get_list_of_types( $return = 'objects' ) {
		$comment_types = demopress_get_comment_types();

		return $return == 'keys' ? array_keys( $comment_types ) : $comment_types;
	}

	protected function init_builders() {
		$this->builders['content'] = array(
			'type' => 'text',
			'list' => demopress()->find_builders( 'text' )
		);
		$this->builders['author']  = array(
			'type' => 'name',
			'list' => demopress()->find_builders( 'name' )
		);
	}

	protected function init_settings() {
		$comment_types = $this->get_list_of_types();
		$post_types    = demopress_get_post_types();

		foreach ( $post_types as $cpt => $post_type ) {
			foreach ( $comment_types as $cmm => $comment_type ) {
				if ( demopress_post_type_support_comment_type( $cpt, $cmm ) ) {
					$_type            = $cpt . '::' . $cmm;
					$_type_for_switch = $cpt . '-' . $cmm;

					$_sections = array(
						array(
							'key'      => 'status',
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( $this->name, 'type-' . $_type, __( "Generate", "demopress" ), __( "Enable this option to generate the posts for this post type and comment type, and show options for the generator controls.", "demopress" ), Type::BOOLEAN, false )->args( array(
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
							EL::i( $this->name, $_type . '-base-count', __( "Number of Comments", "demopress" ), __( "This is number of comments to be generated for each post in this post type.", "demopress" ), Type::ABSINT, 50 )->args( array(
								'min' => 1
							) )
						)
					);

					$_sections  [] = array(
						'label'    => __( "Select posts", "demopress" ),
						'name'     => '',
						'class'    => '',
						'settings' => array(
							EL::i( $this->name, $_type . '-base-method', __( "Method", "demopress" ), '', Type::SELECT, 'rnd' )->data( 'array', array(
								'rnd' => __( "Random published posts", "demopress" ),
								'inc' => __( "Only listed posts", "demopress" ),
								'exc' => __( "All except listed posts", "demopress" )
							) )->args( array(
								'data'          => array( 'switch' => 'demopress-builders-method-' . $_type_for_switch ),
								'wrapper_class' => 'demopress-builder-switch'
							) ),
							EL::i( $this->name, $_type . '-base-random', __( "Random published posts", "demopress" ), __( "Percentage of total posts to take into account for generating comments.", "demopress" ), Type::ABSINT, 100 )->args( array(
								'wrapper_class' => $this->el_wrapper_class( 'demopress-builders-method-' . $_type_for_switch, 'rnd', false ),
								'label_unit'    => '%',
								'min'           => 0,
								'step'          => 5,
								'max'           => 100
							) ),
							EL::i( $this->name, $_type . '-base-include', __( "Only listed posts", "demopress" ), __( "Comma separated list of post ID's.", "demopress" ), Type::TEXT, '' )->args( array(
								'wrapper_class' => $this->el_wrapper_class( 'demopress-builders-method-' . $_type_for_switch, 'inc', true )
							) ),
							EL::i( $this->name, $_type . '-base-exclude', __( "All except listed posts", "demopress" ), __( "Comma separated list of post ID's.", "demopress" ), Type::TEXT, '' )->args( array(
								'wrapper_class' => $this->el_wrapper_class( 'demopress-builders-method-' . $_type_for_switch, 'exc', true )
							) )
						)
					);

					$_settings = array(
						EL::i( $this->name, $_type . '-builder-content', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'text', $this->builders['content']['list'] ) )->args( array(
							'data'          => array( 'switch' => 'demopress-builders-content-' . $_type_for_switch ),
							'wrapper_class' => 'demopress-builder-switch'
						) )
					);

					$_hidden = false;
					foreach ( $this->objects['content'] as $obj ) {
						$settings = $obj->settings( $this->name, $_type, 'content', 'demopress-builders-content-' . $_type_for_switch, $_hidden );

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

					if ( get_option( 'comment_registration' ) == 0 ) {
						$_sections[] = array(
							'key'      => 'authors',
							'label'    => __( "Authors", "demopress" ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( $this->name, $cpt . '-base-authors-registered', __( "Registered users", "demopress" ), __( "Percentage of total comments to be authored by the registered users.", "demopress" ), Type::ABSINT, 40 )->args( array(
									'label_unit' => '%',
									'min'        => 0,
									'step'       => 5,
									'max'        => 100
								) )
							)
						);

						$_settings = array(
							EL::i( $this->name, $_type . '-builder-authors-name', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'name', $this->builders['author']['list'] ) )->args( array(
								'data'          => array( 'switch' => 'demopress-builders-name' . $_type_for_switch ),
								'wrapper_class' => 'demopress-builder-switch'
							) )
						);

						$_hidden = false;
						foreach ( $this->objects['author'] as $obj ) {
							$settings = $obj->settings( $this->name, $_type, 'authors-name', 'demopress-builders-name' . $_type_for_switch, $_hidden );

							if ( ! empty( $settings ) ) {
								$_settings = array_merge( $_settings, $settings );
							}

							$_hidden = true;
						}

						$_sections[] = array(
							'key'      => 'visitors',
							'label'    => __( "Visitors as comment authors", "demopress" ),
							'name'     => '',
							'class'    => '',
							'settings' => $_settings
						);

						$_sections[] = array(
							'key'      => 'domains',
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( $this->name, $_type . '-builder-authors-domains', __( "Email Domains", "demopress" ), __( "Names of one or more email domains to use for emails of generated users. If more than one domain is provided, they will be used at random.", "demopress" ), Type::EXPANDABLE_TEXT, array( $this->_default_domain() ) )
							)
						);
					} else {
						$_sections[0]['settings'][] = EL::i( $this->name, $_type . '-base-authors-registered', '', '', Type::HIDDEN, 100 );
					}

					if ( get_option( 'thread_comments' ) == 1 ) {
						$_sections[] = array(
							'key'      => 'hierarchy',
							'label'    => __( "Hierarchy", "demopress" ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( $this->name, $_type . '-base-toplevel', __( "Top level comments", "demopress" ), __( "Percentage of total comments to generate to be top level comments.", "demopress" ), Type::ABSINT, 50 )->args( array(
									'label_unit' => '%',
									'min'        => 0,
									'step'       => 5,
									'max'        => 100
								) )
							)
						);
					} else {
						$_sections[0]['settings'][] = EL::i( $this->name, $_type . '-base-toplevel', '', '', Type::HIDDEN, 100 );
					}

					$this->settings[ $cpt ] = array(
						'name'     => sprintf( __( "%s for %s", "demopress" ), $comment_type->label, $post_type->label ),
						'sections' => $this->pre_sections( $_sections, $_type ),
						'args'     => array( 'class' => 'demopress-type-settings-hidden' )
					);
				}
			}
		}
	}

	private function _default_domain() {
		return parse_url( site_url(), PHP_URL_HOST );
	}

	protected function generate_item( $type ) {
		list( $cpt, $cmm ) = explode( '::', $type );

		$this->_valid_posts( $cpt, $cmm );

		if ( empty( $this->_list_posts[ $cpt ] ) ) {
			$post_key = array_rand( $this->_list_posts[ $cpt ] );
			$post     = $this->_list_posts[ $post_key ];
			$post_id  = $post->ID;

			$this->_valid_comments( $post_id );

			$comment = array(
				'comment_post_ID' => $post_id
			);
		} else {
			$this->add_log_entry( __( "No posts found.", "demopress" ) );
		}

		$this->item_done();
	}

	private function _valid_comments( $post_id ) {
		if ( ! isset( $this->_list_comments[ $post_id ] ) ) {
			$this->_list_comments = demopress_db()->get_comments_for_post( $post_id );
		}
	}

	private function _valid_posts( $cpt, $cmm ) {
		if ( ! isset( $this->_list_posts[ $cpt ] ) ) {
			$method = $this->get_from_base( $cpt . '::' . $cmm, 'method' );

			if ( $method == 'inc' ) {
				$ids               = explode( ',', $this->get_from_base( $cpt . '::' . $cmm, 'include' ) );
				$this->_list_posts = d4p_clean_ids_list( $ids );
			} else {
				$exclude = array();

				if ( $method == 'inc' ) {
					$ids     = explode( ',', $this->get_from_base( $cpt . '::' . $cmm, 'exclude' ) );
					$exclude = d4p_clean_ids_list( $ids );
				}

				$raw = demopress_db()->get_posts_for_comments( $cpt, $exclude );
				$all = wp_list_pluck( $raw, 'ID' );

				if ( $method == 'rnd' ) {
					shuffle( $all );

					$assign  = $this->get_from_base( $cpt . '::' . $cmm, 'random' );
					$to_pick = ( $assign / 100 ) * count( $all );
					$picked  = array_rand( $all, $to_pick );

					foreach ( $picked as $id ) {
						$this->_list_posts[] = $all[ $id ];
					}
				} else {
					$this->_list_posts = $all;
				}
			}
		}
	}
}
