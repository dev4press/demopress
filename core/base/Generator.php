<?php

namespace Dev4Press\Plugin\DemoPress\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Dev4Press\Plugin\DemoPress\Exception\Builder;

abstract class Generator {
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

	public function add_log_entry( $log, $item_mark = false ) {
		demopress_gen()->add_log_entry( $log, $item_mark, $this->_last );
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

	protected function get_from_base( $type, $name, $sub = false ) {
		if ( isset( $this->_settings[ $type ]['base'][ $name ] ) ) {
			if ( $sub === false ) {
				return $this->_settings[ $type ]['base'][ $name ];
			} else {
				return $this->_settings[ $type ]['base'][ $name ][ $sub ];
			}
		}

		return '';
	}

	protected function get_from_builder( $type, $name ) {
		$builder  = $this->_settings[ $type ]['builder'][ $name ]['value'];
		$settings = $this->_settings[ $type ]['builder'][ $name ]['settings'];

		$_real_name = '';

		foreach ( $this->objects[ $name ] as $real => $obj ) {
			if ( $obj->name == $builder ) {
				$_real_name = $real;
				break;
			}
		}

		$result = $this->objects[ $name ][ $_real_name ]->run( $settings );

		if ( is_wp_error( $result ) ) {
			throw new Builder( 'builder-failed', $result->get_message(), $type, $_real_name );
		}

		return $result;
	}

	abstract public function get_list_of_types();

	abstract protected function init_builders();

	abstract protected function init_settings();

	abstract protected function generate_item( $type );
}
