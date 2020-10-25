<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\Core\DateTime;
use Dev4Press\Core\Plugins\Core;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends Core {
	public $svg_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMC4xLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHdpZHRoPSIzMDBweCIgaGVpZ2h0PSIzMDBweCIgdmlld0JveD0iMCAwIDMwMCAzMDAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMwMCAzMDA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiM5RUEzQTg7fQ0KPC9zdHlsZT4NCjxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0yNTkuMywxMS40SDQyLjVjLTE3LjEsMC0zMSwxMy45LTMxLDMxdjIxNi44YzAsMTcuMSwxMy45LDMxLDMxLDMxaDIxNi44YzE3LjEsMCwzMS0xMy45LDMxLTMxVjQyLjQNCglDMjkwLjMsMjUuMywyNzYuNCwxMS40LDI1OS4zLDExLjR6IE0xMDEuMywyNDcuNkg2Mi42VjExMi4xaDM4LjdWMjQ3LjZ6IE0xNzAuMywyNDcuNmgtMzguN1Y1NGgzOC43VjI0Ny42eiBNMjM5LjIsMjQ2LjhoLTM4LjcNCgl2LTc3LjRoMzguN1YyNDYuOHoiLz4NCjwvc3ZnPg0K';

	public $plugin = 'demopress';

	private $_datetime = null;

	public $generators = array();
	public $builders = array(
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

		demopress_gen();
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

	/** @return \Dev4Press\Core\DateTime */
	public function datetime() {
		return $this->_datetime;
	}

	public function after_setup_theme() {
		$this->_registration();
	}

	public function has_generators_for_group( $group ) {
		foreach ( $this->generators as $generator ) {
			if ( $generator['settings']['group'] == $group ) {
				return true;
			}
		}

		return false;
	}

	public function is_generator_group_valid( $group ) {
		$groups = $this->get_generator_groups();

		return isset( $groups[ $group ] );
	}

	public function get_generator_groups() {
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
	public function find_builders( $type, $settings = array() ) {
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
	public function list_builders( $type, $builders = array() ) {
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
			$settings['group'] = 'plugin';
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

	public function register_builder_text( $name, $label, $description, $settings = array(), $class = '' ) {
		$defaults = array(
			'html'  => false,
			'plain' => false
		);

		$this->builders['text'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Text\\' . $name : $class
		);
	}

	public function register_builder_name( $name, $label, $description, $settings = array(), $class = '' ) {
		$defaults = array();

		$this->builders['name'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Name\\' . $name : $class
		);
	}

	public function register_builder_term( $name, $label, $description, $settings = array(), $class = '' ) {
		$defaults = array();

		$this->builders['term'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Term\\' . $name : $class
		);
	}

	public function register_builder_title( $name, $label, $description, $settings = array(), $class = '' ) {
		$defaults = array();

		$this->builders['title'][ $name ] = array(
			'name'        => $name,
			'label'       => $label,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Title\\' . $name : $class
		);
	}

	public function register_builder_image( $name, $label, $description, $settings = array(), $class = '' ) {
		$defaults = array(
			'remote' => false,
			'local'  => false
		);

		$this->builders['image'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Image\\' . $name : $class
		);
	}

	public function register_builder_video( $name, $label, $description, $settings = array(), $class = '' ) {
		$defaults = array(
			'remote' => false,
			'local'  => false
		);

		$this->builders['video'][ $name ] = array(
			'name'        => $name,
			'slug'        => strtolower( $name ),
			'label'       => $label,
			'description' => $description,
			'settings'    => wp_parse_args( $settings, $defaults ),
			'class'       => empty( $class ) ? 'Dev4Press\\Plugin\\DemoPress\\Data\\Video\\' . $name : $class
		);
	}

	private function _registration() {
		$this->register_builder_text( 'LorIpsumNet', 'Loripsum.net',
			__( "Generate HTML content using Loripsum.net website.", "demopress" ),
			array( 'html' => true )
		);

		$this->register_builder_text( 'LoremIpsum', 'Lorem Ipsum',
			__( "Generate content using PHP Lorem Ipsum class.", "demopress" ),
			array( 'plain' => true )
		);

		$this->register_builder_term( 'LoremIpsum', 'Lorem Ipsum',
			__( "Generate terms using PHP Lorem Ipsum generator.", "demopress" )
		);

		$this->register_builder_term( 'Randomizer', 'Randomizer',
			__( "Generate terms using PHP Randomizer generator.", "demopress" )
		);

		$this->register_builder_name( 'LoremIpsum', 'Lorem Ipsum',
			__( "Generate names using PHP Lorem Ipsum generator.", "demopress" )
		);

		$this->register_builder_name( 'RandomNames', 'Random Names',
			__( "Generate names using PHP Random Names generator.", "demopress" )
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

		$this->register_builder_image( 'picsumphotos', 'Picsum.photos',
			__( "Build image tags linking to the Picsum.photos website.", "demopress" ),
			array( 'remote' => true )
		);

		$this->register_builder_image( 'PixabayCom', 'Pixabay.com',
			__( "Download and use images from Pixabay.com website.", "demopress" ),
			array( 'local' => true )
		);

		$this->register_builder_image( 'PexelsCom', 'Pexels.com',
			__( "Download and use images from Pexels.com website.", "demopress" ),
			array( 'local' => true )
		);

		$this->register_builder_image( 'PicsumPhotos', 'Pexels.com',
			__( "Download and use images from Pexels.com website.", "demopress" ),
			array( 'remote' => true )
		);

		$this->register_builder_video( 'PixabayCom', 'Pixabay.com',
			__( "Build video tags embedding Vimeo videos from Pixabay.com website.", "demopress" ),
			array( 'remote' => true )
		);

		$this->register_generator( 'Users', __( "Users", "demopress" ),
			__( "Generate users with various user roles", "demopress" ),
			array(
				'group' => 'core',
				'icon'  => 'd4p-icon d4p-ui-users',
				'text'  => 'plain',
				'image' => false
			) );
		$this->register_generator( 'Terms', __( "Terms", "demopress" ),
			__( "Generate terms for default and custom taxonomies.", "demopress" ),
			array(
				'group' => 'core',
				'icon'  => 'd4p-icon d4p-ui-terms',
				'text'  => 'plain',
				'image' => false
			) );
		$this->register_generator( 'Posts', __( "Posts", "demopress" ),
			__( "Generate posts for default and custom post types.", "demopress" ),
			array(
				'group' => 'core',
				'icon'  => 'd4p-icon d4p-ui-paste',
				'text'  => true,
				'image' => true
			) );
		$this->register_generator( 'Comments', __( "Comments", "demopress" ),
			__( "Generate comments for post types supporting comments.", "demopress" ),
			array(
				'group' => 'core',
				'icon'  => 'd4p-icon d4p-ui-comments',
				'text'  => true,
				'image' => true
			) );

		if ( d4p_has_bbpress() ) {
			$this->register_generator( 'bbPress', __( "bbPress Forums", "demopress" ),
				__( "Generate forums, topics and replies for bbPress powered forums.", "demopress" ),
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
