<?php

namespace Dev4Press\Plugin\DemoPress\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Dev4Press\Plugin\DemoPress\Exception\Builder;
use WP_User_Query;

abstract class Generator {
	protected $_terms_cache = array();
	protected $_posts_cache = array();
	protected $_users_cache = array();

	protected $_settings = array();
	protected $_progress = array();
	protected $_types = array();
	protected $_last = 0;

	public $name = '';
	public $objects = array();
	public $builders = array();
	public $settings = array();

	public function __construct() {
		$this->init_builders();

		foreach ( $this->builders as $option => $value ) {
			foreach ( $value['list'] as $item ) {
				$this->objects[ $option ][ $item ] = demopress()->get_builder( $value['type'], $item );
			}
		}

		$this->init_settings();
	}

	/** @return Generator */
	public static function instance() {
		static $instance = array();

		$class = get_called_class();

		if ( ! isset( $instance[ $class ] ) ) {
			$instance[ $class ] = new $class();
		}

		return $instance[ $class ];
	}

	public function get_cleanup_types() {
		$types = $this->get_list_of_types();

		return wp_list_pluck($types, 'label', 'name');
	}

	public function get_cleanup_notice() {
		return '';
	}

	public function add_log_entry( $log, $item_mark = false ) {
		demopress_gen()->add_log_entry( $log, $item_mark, $this->_last );
	}

	public function settings_for_processing() {
		$list = array();

		foreach ( $this->settings as $obj ) {
			foreach ( $obj['sections'] as $s ) {
				foreach ( $s['settings'] as $o ) {
					if ( ! empty( $o->type ) ) {
						$list[] = $o;
					}
				}
			}
		}

		return $list;
	}

	public function show() {
		$path = DEMOPRESS_PATH . 'forms/generators/' . $this->name . '.php';

		if ( file_exists( $path ) ) {
			include( $path );
		}
	}

	public function run() {
		$this->_settings = demopress_gen()->settings;
		$this->_progress = demopress_gen()->progress;
		$this->_types    = array_keys( $this->_settings );

		$defaults = array(
			'types'   => array(),
			'current' => '',
			'count'   => 0
		);

		$this->_progress = wp_parse_args( $this->_progress, $defaults );

		$this->_last = microtime( true );
		$this->add_log_entry( 'Thread Started', true );

		$this->generate();

		$this->_last = microtime( true );
		$this->add_log_entry( 'Thread Ended', true );

		if ( ! $this->has_more_to_do() ) {
			demopress_gen()->change_status( 'finished' );
		}
	}

	public function get_user_roles() {
		return array(
			'administrator' => __( "Administrator", "demopress" ),
			'editor'        => __( "Editor", "demopress" ),
			'author'        => __( "Author", "demopress" ),
			'contributor'   => __( "Contributor", "demopress" ),
			'subscriber'    => __( "Subscriber", "demopress" )
		);
	}

	public function get_settings() {
		$list = array();

		foreach ( $this->settings as $obj ) {
			foreach ( $obj['settings'] as $o ) {
				$list[] = $o;
			}
		}

		return $list;
	}

	public function process_request( $req ) {
		$types = array();

		foreach ( $req as $t => $value ) {
			if ( substr( $t, 0, 4 ) == 'type' ) {
				$type = substr( $t, 5 );

				if ( $value === false ) {
					continue;
				}

				$out = array(
					'base'    => array(),
					'builder' => array()
				);

				foreach ( $req as $code => $val ) {
					if ( $code == $t || substr( $code, 0, strlen( $type ) ) != $type ) {
						continue;
					}

					$rc    = substr( $code, strlen( $type ) + 1 );
					$parts = explode( '-', $rc );

					if ( count( $parts ) == 2 ) {
						if ( $parts[0] == 'base' ) {
							$out['base'][ $parts[1] ] = $val;
						} else if ( $parts[0] == 'builder' ) {
							$out['builder'][ $parts[1] ] = array(
								'value'    => $val,
								'settings' => array()
							);
						}
					} else if ( $parts[0] == 'base' ) {
						if ( count( $parts ) == 3 ) {
							$out['base'][ $parts[1] ][ $parts[2] ] = $val;
						} else if ( count( $parts ) == 4 ) {
							$out['base'][ $parts[1] ][ $parts[2] ][ $parts[3] ] = $val;
						}
					}
				}

				foreach ( $out['builder'] as $code => $data ) {
					$prefix = $type . '-builder-' . $code . '-' . $data['value'] . '-';

					foreach ( $req as $key => $value ) {
						if ( substr( $key, 0, strlen( $prefix ) ) == $prefix ) {
							$name = substr( $key, strlen( $prefix ) );

							$data['settings'][ $name ] = $value;
						}
					}

					$out['builder'][ $code ] = $data;
				}

				$types[ $type ] = $out;
			}
		}

		return $types;
	}

	protected function generate() {
		while ( $this->can_continue() && $this->has_more_to_do() ) {
			$this->add_log_entry(
				sprintf( __( "Item %s of %s for '%s'.", "demopress" ),
					$this->current_item(), $this->current_type_total_item(), $this->current_type() ), true );

			try {
				$this->generate_item( $this->current_type() );
			} catch ( Builder $e ) {
				$this->add_log_entry(
					sprintf( __( "Generator failed! Builder %s for %s error with message '%s'.", "demopress" ),
						$e->getBuilder(), ucfirst( $e->getType() ), $e->getMessage() ) );

				demopress_gen()->change_status( 'error' );

				break;
			}
		}

		$this->generate_thread_finished( $this->current_type() );
	}

	protected function has_more_to_do() {
		if ( empty( $this->_progress['current'] ) ) {
			$this->_progress['current'] = $this->_types[0];
		}

		$more = $this->_progress['count'] < $this->_settings[ $this->current_type() ]['base']['count'];

		if ( ! $more ) {
			$this->_progress['types'][] = $this->_progress['current'];

			foreach ( $this->_types as $type ) {
				if ( ! in_array( $type, $this->_progress['types'] ) ) {
					$this->_progress['current'] = $type;
					$this->_progress['count']   = 0;

					$more = true;

					break;
				}
			}
		}

		return $more;
	}

	protected function current_type() {
		return $this->_progress['current'];
	}

	protected function current_item() {
		return $this->_progress['count'] + 1;
	}

	protected function current_type_total_item() {
		return $this->_settings[ $this->current_type() ]['base']['count'];
	}

	protected function can_continue() {
		$time = microtime( true ) - $this->_last;

		if ( $time >= demopress_gen()->timeout() ) {
			return false;
		}

		return true;
	}

	protected function item_done() {
		$this->_progress['count'] ++;

		demopress_gen()->progress = $this->_progress;
		demopress_gen()->save( true );

		usleep( 5000 );
	}

	protected function get_from_base( $type, $name, $sub = false, $default = '' ) {
		if ( isset( $this->_settings[ $type ]['base'][ $name ] ) ) {
			if ( $sub === false ) {
				return $this->_settings[ $type ]['base'][ $name ];
			} else {
				return $this->_settings[ $type ]['base'][ $name ][ $sub ];
			}
		}

		return '';
	}

	/** @return \Dev4Press\Plugin\DemoPress\Base\Builder */
	protected function get_the_builder( $type, $name ) {
		$builder = $this->_settings[ $type ]['builder'][ $name ]['value'];

		$_real_name = '';

		foreach ( $this->objects[ $name ] as $real => $obj ) {
			if ( $obj->name == $builder ) {
				$_real_name = $real;
				break;
			}
		}

		if ( isset( $this->objects[ $name ][ $_real_name ] ) ) {
			return $this->objects[ $name ][ $_real_name ];
		}

		throw new Builder( 'builder-missing', __( "Requested builder not found.", "demopress" ), $type, $name );
	}

	protected function get_builder_scope( $type, $name ) {
		$builder = $this->get_the_builder( $type, $name );

		return $builder->scope;
	}

	protected function get_from_builder( $type, $name ) {
		$builder  = $this->get_the_builder( $type, $name );
		$settings = $this->_settings[ $type ]['builder'][ $name ]['settings'];

		$result = $builder->run( $settings );

		if ( is_wp_error( $result ) ) {
			throw new Builder( 'builder-failed', $result->get_message(), $type, $name );
		}

		return $result;
	}

	protected function _cache_users( $roles = array() ) {
		if ( empty( $this->_users_cache ) ) {
			$query = new WP_User_Query( array(
				'role__in' => $roles,
				'fields'   => 'ID',
				'number'   => - 1
			) );

			$this->_users_cache = $query->get_results();
		}
	}

	protected function _cache_posts( $type, $child_of = 0 ) {
		if ( empty( $this->_posts_cache[ $type ] ) ) {
			if ( is_post_type_hierarchical( $type ) ) {
				$args = array( 'post_type' => $type );

				if ( $child_of > 0 ) {
					$args['child_of'] = $child_of;
				}

				$pages = get_pages( $args );

				$this->_posts_cache[ $type ] = wp_list_pluck( $pages, 'ID' );
			} else {
				$raw = demopress_db()->get_posts_for_post_type( $type );

				$this->_posts_cache[ $type ] = wp_list_pluck( $raw, 'ID' );
			}
		}
	}

	protected function _cache_terms( $tax, $child_of = 0 ) {
		if ( empty( $this->_terms_cache[ $tax ] ) ) {
			$args = array( 'fields' => 'ids' );

			if ( is_taxonomy_hierarchical( $tax ) && $child_of > 0 ) {
				$args['child_of'] = $child_of;
			}

			$this->_terms_cache[ $tax ] = get_terms( 'category', $args );
		}
	}

	protected function el_wrapper_class( $class, $name, $hidden = false ) {
		return $class . '-switch ' . $class . '-data-' . $name . ( $hidden ? ' demopress-is-hidden' : '' );
	}

	protected function pre_sections( $sections, $type ) {
		return $sections;
	}

	protected function generate_thread_finished( $type ) {

	}

	abstract public function get_list_of_types( $return = 'objects' );

	abstract protected function init_builders();

	abstract protected function init_settings();

	abstract protected function generate_item( $type );
}
