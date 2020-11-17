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

	public function get_cleanup_count( $type = '' ) {
		list( $cpt, $cmm ) = explode( '::', $type );

		return demopress_db()->get_comments_for_cleanup( $cpt, $cmm, true );
	}

	public function run_cleanup( $type ) {
		list( $cpt, $cmm ) = explode( '::', $type );

		$ids = demopress_db()->get_comments_for_cleanup( $cpt, $cmm );

		if ( ! empty( $ids ) ) {
			demopress_db()->run_comments_cleanup( $ids );
		}

		return count( $ids );
	}

	public function get_cleanup_notice() {
		return array( __( "Make sure to remove comments before posts these comments belong too. Comments are always removed based on the link to posts. If you remove posts before comments, you will see unlinked comments in the Comments panel.", "demopress" ) );
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
								EL::i( $this->name, $_type . '-base-registered', __( "Registered users", "demopress" ), __( "Percentage of total comments to be authored by the registered users.", "demopress" ), Type::ABSINT, 40 )->args( array(
									'label_unit' => '%',
									'min'        => 0,
									'step'       => 5,
									'max'        => 100
								) )
							)
						);

						$_settings = array(
							EL::i( $this->name, $_type . '-builder-author', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'name', $this->builders['author']['list'] ) )->args( array(
								'data'          => array( 'switch' => 'demopress-builders-name' . $_type_for_switch ),
								'wrapper_class' => 'demopress-builder-switch'
							) )
						);

						$_hidden = false;
						foreach ( $this->objects['author'] as $obj ) {
							$settings = $obj->settings( $this->name, $_type, 'author', 'demopress-builders-name' . $_type_for_switch, $_hidden );

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
								EL::i( $this->name, $_type . '-base-domains', __( "Email Domains", "demopress" ), __( "Names of one or more email domains to use for emails of generated users. If more than one domain is provided, they will be used at random.", "demopress" ), Type::EXPANDABLE_TEXT, array( $this->_default_domain() ) )
							)
						);
					} else {
						$_sections[0]['settings'][] = EL::i( $this->name, $_type . '-base-registered', '', '', Type::HIDDEN, 100 );
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

	protected function generate_item( $type ) {
		list( $cpt, $cmm ) = explode( '::', $type );

		$this->_cache_users();
		$this->_valid_posts( $type, $cpt, $cmm );

		if ( ! empty( $this->_list_posts[ $cpt ] ) ) {
			$post_key   = array_rand( $this->_list_posts[ $cpt ] );
			$post       = $this->_list_posts[ $cpt ][ $post_key ];
			$post_id    = $post->ID;
			$date_start = $post->post_date;

			$this->_valid_comments( $post_id );

			$comment = array(
				'comment_post_ID' => $post_id,
				'comment_content' => $this->get_from_builder( $type, 'content' ),
				'comment_author_IP' => $this->get_random_ip()
			);

			if ( count( $this->_list_comments[ $post_id ] ) > 0 ) {
				$_last_comment = end( $this->_list_comments[ $post_id ] );
				$date_start    = $_last_comment->comment_date;

				if ( $this->get_from_base( $type, 'toplevel' ) < 100 ) {
					$toplevel = ceil( $this->get_from_base( $type, 'toplevel' ) * ( $this->get_from_base( $type, 'count' ) / 100 ) );

					if ( $toplevel >= $this->current_item() + 1 ) {
						$parent                    = $this->_list_comments[ $post_id ][ array_rand( $this->_list_comments[ $post_id ] ) ];
						$comment['comment_parent'] = $parent->comment_ID;
						$date_start                = $parent->comment_date;
					}
				}
			}

			$for_user = true;
			$reg_part = $this->get_from_base( $type, 'registered' );

			if ( $reg_part < 100 ) {
				if ( mt_rand( 0, 100 ) > $reg_part ) {
					$for_user    = false;
					$author_name = $this->get_from_builder( $type, 'author' );
					$name        = explode( ' ', $author_name );

					$comment['comment_author']       = $author_name;
					$comment['comment_author_email'] = $this->_generate_email( $type, strtolower( $name[0] . '.' . $name[1] ) );
				}
			}

			if ( $for_user ) {
				$user_id = $this->_users_cache[ array_rand( $this->_users_cache ) ];
				$user_id = absint( $user_id );
				$user    = get_user_by( 'id', $user_id );

				$comment['comment_author']       = $user->display_name;
				$comment['comment_author_email'] = $user->user_email;
				$comment['comment_author_url']   = $user->user_url;
				$comment['user_id']              = $user_id;
			}

			$comment['comment_date'] = $this->_get_random_publish_date_from( $date_start );

			$comment_id = wp_insert_comment( $comment );

			if ( ! is_wp_error( $comment_id ) && $comment_id !== false ) {
				$this->_list_comments[ $post_id ][] = (object) array(
					'comment_ID'   => $comment_id,
					'comment_date' => $comment['comment_date']
				);

				update_comment_meta( $comment_id, '_demopress_generated_content', '1' );

				$this->add_log_entry(
					sprintf( __( "Added Comment - ID: %s", "demopress" ),
						$comment_id ) );
			} else {
				$this->add_log_entry( __( "Failed creating the comment.", "demopress" ) );
			}
		} else {
			$this->add_log_entry( __( "No posts found.", "demopress" ) );
		}

		$this->item_done();
	}

	private function _valid_comments( $post_id ) {
		if ( ! isset( $this->_list_comments[ $post_id ] ) ) {
			$this->_list_comments[ $post_id ] = demopress_db()->get_comments_for_post( $post_id );
		}
	}

	private function _valid_posts( $type, $cpt, $cmm ) {
		if ( ! isset( $this->_list_posts[ $cpt ] ) ) {
			$method = $this->get_from_base( $type, 'method' );

			$this->_list_posts[ $cpt ] = array();

			$exclude = array();
			$include = array();

			if ( $method == 'inc' ) {
				$ids     = explode( ',', $this->get_from_base( $type, 'include' ) );
				$include = d4p_clean_ids_list( $ids );
			} else if ( $method == 'exc' ) {
				$ids     = explode( ',', $this->get_from_base( $type, 'exclude' ) );
				$exclude = d4p_clean_ids_list( $ids );
			}

			$all = demopress_db()->get_posts_for_comments( $cpt, $include, $exclude );

			if ( $method == 'rnd' ) {
				shuffle( $all );

				$assign  = $this->get_from_base( $type, 'random' );
				$to_pick = ( $assign / 100 ) * count( $all );
				$picked  = array_rand( $all, $to_pick );

				foreach ( $picked as $id ) {
					$this->_list_posts[ $cpt ][] = $all[ $id ];
				}
			} else {
				$this->_list_posts[ $cpt ] = $all;
			}
		}
	}

	private function _generate_email( $type, $slug ) {
		$domains = $this->get_from_base( $type, 'domains' );

		if ( empty( $domains ) ) {
			$domains = array( $this->_default_domain() );
		}

		$domain = $domains[ array_rand( $domains ) ];

		return $slug . '@' . $domain;
	}
}
