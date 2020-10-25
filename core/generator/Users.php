<?php

namespace Dev4Press\Plugin\DEMOPRESS\Generator;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DEMOPRESS\Base\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Users extends Generator {
	public $name = 'users';

	protected function init_builders() {
		$this->builders['name']  = array(
			'type' => 'name',
			'list' => demopress()->find_builders( 'name' )
		);
		$this->builders['about'] = array(
			'type' => 'text',
			'list' => demopress()->find_builders( 'text', array( 'plain' ) )
		);
	}

	protected function init_settings() {
		$_sections = array();

		$_settings = array(
			EL::i( 'users', 'users-builder-name', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'name', $this->builders['name']['list'] ) )->args( array( 'class' => 'demopress-builders-name' ) )
		);

		$_hidden = false;
		foreach ( $this->objects['name'] as $obj ) {
			$settings = $obj->settings( 'users', 'users', 'name', 'demopress-builders-name', $_hidden );

			if ( ! empty( $settings ) ) {
				$_username_settings = array_merge( $_username_settings, $settings );
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
			EL::i( 'users', 'users-base-about', __( "Status", "demopress" ), '', Type::BOOLEAN, true ),
			EL::i( 'users', 'users-builder-about', __( "Generate with", "demopress" ), '', Type::SELECT, '' )->data( 'array', demopress()->list_builders( 'text', $this->builders['about']['list'] ) )->args( array( 'class' => 'demopress-builders-about' ) )
		);

		$_hidden = false;
		foreach ( $this->objects['about'] as $obj ) {
			$settings = $obj->settings( 'users', 'users', 'about', 'demopress-builders-about', $_hidden );

			if ( ! empty( $settings ) ) {
				$_settings = array_merge( $_settings, $settings );
			}

			$_hidden = true;
		}

		$_sections[] = array(
			'label'    => __( "About", "demopress" ),
			'name'     => '',
			'class'    => '',
			'settings' => $_settings
		);

		$this->settings = array(
			'basic' => array(
				'name'     => __( "Users to generate", "demopress" ),
				'sections' => array(
					array(
						'label'    => '',
						'name'     => '',
						'class'    => '',
						'settings' => array(
							EL::i( 'users', 'type-users', '', '', Type::HIDDEN, 'on' ),
							EL::i( 'users', 'users-base-count', __( "Number of Users", "demopress" ), '', Type::ABSINT, 5 )->args( array( 'min' => 1 ) )
						)
					)
				)
			),
			'data'  => array(
				'name'     => __( "Data for users", "demopress" ),
				'sections' => array(
					array(
						'label'    => __( "Roles", "demopress" ),
						'name'     => '',
						'class'    => '',
						'settings' => array(
							EL::i( 'users', 'users-base-roles', __( "Roles for Users", "demopress" ), __( "Roles will be randomly assigned using the checked roles. If no roles are enabled, all users will be assigned 'Subscriber' role.", "demopress" ), Type::CHECKBOXES, array( 'subscriber' ) )->data( 'array', $this->get_user_roles() )
						)
					),
					array(
						'label'    => __( "Email", "demopress" ),
						'name'     => '',
						'class'    => '',
						'settings' => array(
							EL::i( 'users', 'users-base-domains', __( "Email Domains", "demopress" ), __( "Names of one or more email domains to use for emails of generated users. If more than one domain is provided, they will be used at random.", "demopress" ), Type::EXPANDABLE_TEXT, array( $this->_default_domain() ) )
						)
					),
					array(
						'label'    => __( "Password", "demopress" ),
						'name'     => '',
						'class'    => '',
						'settings' => array(
							EL::i( 'users', 'users-base-password', __( "Default Password", "demopress" ), __( "All users will be created with the same password. If this field is empty, each user will get random password.", "demopress" ), Type::TEXT )
						)
					)
				)
			),
			'build' => array(
				'name'     => __( "Generated data for users", "demopress" ),
				'sections' => $_sections
			)
		);
	}

	protected function generate_item( $type ) {
		$user = array(
			'name'     => $this->get_from_builder( 'users', 'name' ),
			'password' => $this->get_from_base( 'users', 'password' ),
			'about'    => ''
		);

		$user['username'] = sanitize_title( $user['name'] );
		$user['email']    = $this->_generate_email( $user['username'] );

		$name              = explode( ' ', $user['name'] );
		$user['firstname'] = $name[0];
		$user['lastname']  = $name[1];

		if ( empty( $user['password'] ) ) {
			$user['password'] = wp_generate_password();
		}

		if ( $this->get_from_base( 'users', 'about' ) ) {
			$user['about'] = $this->get_from_builder( 'users', 'about' );
		}

		if ( $this->_check_user( $user ) ) {
			$user_id = wp_create_user( $user['username'], $user['password'], $user['email'] );

			if ( ! is_wp_error( $user_id ) ) {
				$u = get_userdata( $user_id );
				$u->set_role( $this->_generate_role() );

				wp_update_user( array(
					'ID'                        => $user_id,
					'description'               => $user['about'],
					'nickname'                  => $user['firstname'],
					'first_name'                => $user['firstname'],
					'last_name'                 => $user['lastname'],
					'display_name'              => $user['name'],
					'_demopress_auto_generated' => '1'
				) );
			}

			$this->add_log_entry(
				sprintf( __( "Added User - ID: %s, Username: '%s'", "demopress" ),
					$user_id, $user['username'] ) );
			$this->add_log_entry(
				sprintf( __( "Email: '%s', Name: '%s'", "demopress" ),
					$user['email'], $user['name'] ) );

			$this->item_done();
		}
	}

	private function _default_domain() {
		return parse_url( site_url(), PHP_URL_HOST );
	}

	private function _generate_email( $slug ) {
		$domains = $this->get_from_base( 'users', 'domains' );

		if ( empty( $domains ) ) {
			$domains = array( $this->_default_domain() );
		}

		$domain = $domains[ array_rand( $domains ) ];

		return $slug . '@' . $domain;
	}

	private function _generate_role() {
		$roles = $this->get_from_base( 'users', 'roles' );

		if ( empty( $roles ) ) {
			$roles = array( 'subscriber' );
		}

		return $roles[ array_rand( $roles ) ];
	}

	private function _check_user( $user ) {
		$user_from_name = username_exists( $user['username'] );
		$user_from_mail = email_exists( $user['email'] );

		return $user_from_mail === false && $user_from_name === false;
	}
}
