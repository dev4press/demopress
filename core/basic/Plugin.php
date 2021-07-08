<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\v35\Core\DateTime;
use Dev4Press\v35\Core\Plugins\Core;
use WP_Error;
use function Dev4Press\v35\Functions\bbPress\is_active;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends Core {
	public $plugin = 'demopress';

	private $_datetime = null;

	public $generators = array();
	public $builders = array(
		'html'  => array(),
		'text'  => array(),
		'term'  => array(),
		'title' => array(),
		'name'  => array(),
		'image' => array(),
		'video' => array()
	);

	public function __construct() {
		$this->url       = DEMOPRESS_URL;
		$this->_datetime = new DateTime();

		parent::__construct();

		add_action( 'after_setup_theme', array( $this, 'prepare' ), 100000 );

		demopress_gen();
	}

	public static function instance() : Plugin {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Plugin();
		}

		return $instance;
	}

	public function run() {
		define( 'DEMOPRESS_WPV', intval( $this->wp_version ) );
		define( 'DEMOPRESS_WPV_MAJOR', substr( $this->cms_version, 0, 3 ) );
		define( 'DEMOPRESS_WP_VERSION', $this->cms_version );

		do_action( 'demopress_load_settings' );
		do_action( 'demopress_plugin_init' );
	}

	public function s() {
		return demopress_settings();
	}

	public function datetime() : DateTime {
		return $this->_datetime;
	}

	public function prepare() {
		$this->_registration();
	}

	public function has_generators_for_group( $group ) : bool {
		foreach ( $this->generators as $generator ) {
			if ( $generator['settings']['group'] == $group ) {
				return true;
			}
		}

		return false;
	}

	public function is_generator_group_valid( $group ) : bool {
		$groups = $this->get_generator_groups();

		return isset( $groups[ $group ] );
	}

	public function get_generator_groups() : array {
		return array(
			'core'    => array(
				'label' => __( "WordPress Core", "demopress" ),
				'icon'  => 'brand-wordpress'
			),
			'plugins' => array(
				'label' => __( "Third Party Plugins", "demopress" ),
				'icon'  => 'ui-puzzle'
			)
		);
	}

	public function get_generator_label( $name ) {
		if ( isset( $this->generators[ $name ] ) ) {
			return $this->generators[ $name ]['label'];
		}

		foreach ( $this->generators as $generator ) {
			if ( $name == $generator['slug'] ) {
				return $generator['label'];
			}
		}

		return __( "Unspecified", "demopress" );
	}

	/** @return \Dev4Press\Plugin\DemoPress\Base\Generator|\WP_Error */
	public function get_generator( $name ) {
		foreach ( $this->generators as $code => $obj ) {
			if ( $name === $code || $obj['slug'] === $name ) {
				$class = $obj['class'];

				return new $class();
			}
		}

		return new WP_Error( 'generator_missing', __( "Requested generator not found.", "demopress" ) );
	}

	/** @return \Dev4Press\Plugin\DemoPress\Base\Builder|\WP_Error */
	public function get_builder( $type, $name ) {
		if ( isset( $this->builders[ $type ][ $name ] ) ) {
			$class = $this->builders[ $type ][ $name ]['class'];

			return new $class();
		}

		return new WP_Error( 'builder_missing', __( "Requested builder not found.", "demopress" ) );
	}

	/** @return array */
	public function find_builders( $type, $settings = array() ) : array {
		$found = array();

		foreach ( $this->builders[ $type ] as $code => $builder ) {
			if ( empty( $settings ) ) {
				$found[] = $code;
			} else {
				$is = true;

				foreach ( $settings as $feature ) {
					if ( ! isset( $builder['settings'][ $feature ] ) || $builder['settings'][ $feature ] === false ) {
						$is = false;
					}
				}

				if ( $is ) {
					$found[] = $code;
				}
			}
		}

		return $found;
	}

	/** @return array */
	public function list_builders( $type, $builders = array() ) : array {
		$list = array();

		foreach ( $this->builders[ $type ] as $code => $builder ) {
			if ( in_array( $code, $builders ) ) {
				$list[ $builder['slug'] ] = $builder['label'];
			}
		}

		return $list;
	}

	public function register_generator( $name, $label, $description, $settings = array(), $class = '' ) {
		$defaults = array(
			'group' => '',
			'icon'  => '',
			'text'  => array( 'html', 'plain' ),
			'image' => array( 'remote', 'local' )
		);

		$settings = wp_parse_args( $settings, $defaults );

		if ( ! $this->is_generator_group_valid( $settings['group'] ) ) {
			$settings['group'] = 'plugins';
		}

		$this->generators[ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'description' => $description,
			'settings'    => $settings,
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Generator\\' . $name : $class
		);
	}

	public function register_builder_text( $name, $label, $description, $remote = false, $settings = array(), $class = '' ) {
		$defaults = array();

		$this->builders['text'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'remote'      => $remote,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Text\\' . $name : $class
		);
	}

	public function register_builder_html( $name, $label, $description, $remote = false, $settings = array(), $class = '' ) {
		$defaults = array();

		$this->builders['html'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'remote'      => $remote,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\HTML\\' . $name : $class
		);
	}

	public function register_builder_name( $name, $label, $description, $remote = false, $settings = array(), $class = '' ) {
		$defaults = array();

		$this->builders['name'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'remote'      => $remote,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Name\\' . $name : $class
		);
	}

	public function register_builder_term( $name, $label, $description, $remote = false, $settings = array(), $class = '' ) {
		$defaults = array();

		$this->builders['term'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'remote'      => $remote,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Term\\' . $name : $class
		);
	}

	public function register_builder_title( $name, $label, $description, $remote = false, $settings = array(), $class = '' ) {
		$defaults = array();

		$this->builders['title'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'remote'      => $remote,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Title\\' . $name : $class
		);
	}

	public function register_builder_image( $name, $label, $description, $remote = false, $settings = array(), $class = '' ) {
		$defaults = array(
			'remote' => false,
			'local'  => false
		);

		$this->builders['image'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'remote'      => $remote,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Image\\' . $name : $class
		);
	}

	public function register_builder_video( $name, $label, $description, $remote = false, $settings = array(), $class = '' ) {
		$defaults = array(
			'remote' => false,
			'local'  => false
		);

		$this->builders['video'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'remote'      => $remote,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Video\\' . $name : $class
		);
	}

	private function _registration() {
		$this->register_builder_html( 'LoremIpsum', 'Lorem Ipsum',
			__( "Generate HTML content using PHP Lorem Ipsum class.", "demopress" )
		);

		$this->register_builder_html( 'LorIpsumNet', 'Loripsum.net',
			__( "Generate HTML content using Loripsum.net website.", "demopress" ),
			true
		);

		$this->register_builder_text( 'LoremIpsum', 'Lorem Ipsum',
			__( "Generate plaintext content using PHP Lorem Ipsum class.", "demopress" )
		);

		$this->register_builder_text( 'LorIpsumNet', 'Loripsum.net',
			__( "Generate plaintext content using Loripsum.net website.", "demopress" ),
			true
		);

		$this->register_builder_term( 'LoremIpsum', 'Lorem Ipsum',
			__( "Generate terms using PHP Lorem Ipsum generator.", "demopress" )
		);

		$this->register_builder_term( 'Randomizer', 'Randomizer',
			__( "Generate terms using PHP Randomizer generator.", "demopress" )
		);

		$this->register_builder_name( 'RandomNames', 'Random Names',
			__( "Generate names using PHP Random Names generator.", "demopress" )
		);

		$this->register_builder_name( 'LoremIpsum', 'Lorem Ipsum',
			__( "Generate names using PHP Lorem Ipsum generator.", "demopress" )
		);

		$this->register_builder_name( 'Randomizer', 'Randomizer',
			__( "Generate names using PHP Randomizer generator.", "demopress" )
		);

		$this->register_builder_title( 'LoremIpsum', 'Lorem Ipsum',
			__( "Generate titles using PHP Lorem Ipsum generator.", "demopress" )
		);

		$this->register_builder_title( 'Randomizer', 'Randomizer',
			__( "Generate titles using PHP Randomizer generator.", "demopress" )
		);

		$this->register_builder_title( 'Listed', 'Listed Titles',
			__( "Provide the list of titles to use.", "demopress" )
		);

		$this->register_builder_image( 'Placeholder', 'Placeholder',
			__( "Build placeholder image with random colors and basic text.", "demopress" ),
			false, array( 'local' => true )
		);

		$this->register_builder_image( 'LocalStorage', 'Local Storage',
			__( "Get images from the local storage directory.", "demopress" ),
			false, array( 'local' => true )
		);

		$_api_key = demopress_settings()->get( 'pixabay_api_key' );
		if ( ! empty( $_api_key ) ) {
			$this->register_builder_image( 'PixabayCom', 'Pixabay.com',
				__( "Download and use images from Pixabay.com website.", "demopress" ),
				true, array(
					'local'       => true,
					'api_key'     => $_api_key,
					'full_access' => demopress_settings()->get( 'pixabay_full_access' )
				)
			);

			$this->register_builder_video( 'PixabayCom', 'Pixabay.com',
				__( "Build video tags embedding Vimeo videos from Pixabay.com website.", "demopress" ),
				true, array( 'local' => true, 'remote' => true, 'api_key' => $_api_key )
			);
		}

		$_api_key = demopress_settings()->get( 'pexels_api_key' );
		if ( ! empty( $_api_key ) ) {
			$this->register_builder_image( 'PexelsCom', 'Pexels.com',
				__( "Download and use images from Pexels.com website.", "demopress" ),
				true, array( 'local' => true, 'api_key' => $_api_key )
			);

			$this->register_builder_video( 'PexelsCom', 'Pixabay.com',
				__( "Build video tags embedding Vimeo videos from Pexels.com website.", "demopress" ),
				true, array( 'remote' => true, 'api_key' => $_api_key )
			);
		}

		$this->register_generator( 'Users', __( "Users", "demopress" ),
			__( "Generate users with various user roles, one or more domains to use for random emails, preset or random password, generated name and about information.", "demopress" ),
			array(
				'group' => 'core',
				'icon'  => 'd4p-icon d4p-ui-users',
				'text'  => 'plain',
				'image' => false
			) );
		$this->register_generator( 'Terms', __( "Terms", "demopress" ),
			__( "Generate terms for default and custom taxonomies, with terms hierarchy support and ability to generate random term name and optional term description.", "demopress" ),
			array(
				'group' => 'core',
				'icon'  => 'd4p-icon d4p-ui-tags',
				'text'  => 'plain',
				'image' => false
			) );
		$this->register_generator( 'Posts', __( "Posts", "demopress" ),
			__( "Generate posts for default and custom post types, with hierarchy support and ability to get a random featured image and generate all other post data.", "demopress" ),
			array(
				'group' => 'core',
				'icon'  => 'd4p-icon d4p-ui-paste',
				'text'  => true,
				'image' => true
			) );
		$this->register_generator( 'Comments', __( "Comments", "demopress" ),
			__( "Generate comments for post types supporting comments, including support for threaded comments and ability to generate random comment authors information.", "demopress" ),
			array(
				'group' => 'core',
				'icon'  => 'd4p-icon d4p-ui-comments',
				'text'  => true,
				'image' => true
			) );

		if ( is_active() ) {
			$this->register_generator( 'bbPress', __( "bbPress Forums", "demopress" ),
				__( "Generate forums, topics and replies for bbPress powered forums, with support for generating different data and conforming to the bbPress content specs.", "demopress" ),
				array(
					'group' => 'plugins',
					'icon'  => 'd4p-icon d4p-logo-bbpress',
					'text'  => true,
					'image' => true
				) );
		}

		do_action( 'demopress_register_generators_and_builders' );
	}
}
