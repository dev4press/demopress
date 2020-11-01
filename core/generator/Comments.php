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
					$_type = $cpt . '::' . $cmm;

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
							EL::i( $this->name, $_type . '-base-count', __( "Comments per post", "demopress" ), __( "This is number of comments to be generated for each post in this post type.", "demopress" ), Type::RANGE_ABSINT, '1=>10' )->args( array(
								'min' => 1
							) ),
							EL::i( $this->name, $_type . '-base-affected', __( "For how many posts", "demopress" ), __( "Percentage of total posts to take into account for generating comments.", "demopress" ), Type::ABSINT, 100 )->args( array(
								'label_unit' => '%',
								'min'        => 0,
								'step'       => 5,
								'max'        => 100
							) )
						)
					);

					$_settings = array(
						EL::i( $this->name, $_type . '-builder-content', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'text', $this->builders['content']['list'] ) )->args( array(
							'data'          => array( 'switch' => 'demopress-builders-content-' . $_type ),
							'wrapper_class' => 'demopress-builder-switch'
						) )
					);

					$_hidden = false;
					foreach ( $this->objects['content'] as $obj ) {
						$settings = $obj->settings( $this->name, $_type, 'content', 'demopress-builders-content-' . $_type, $_hidden );

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
								'data'          => array( 'switch' => 'demopress-builders-name' . $_type ),
								'wrapper_class' => 'demopress-builder-switch'
							) )
						);

						$_hidden = false;
						foreach ( $this->objects['name'] as $obj ) {
							$settings = $obj->settings( $this->name, $_type, 'authors-name', 'demopress-builders-name' . $_type, $_hidden );

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

	}
}
