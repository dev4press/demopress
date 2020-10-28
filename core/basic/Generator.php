<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Generator {
	private $_timeout = 25;
	private $_generator = null;
	private $_init = false;

	public $status = 'idle';
	public $type = '';
	public $started = 0;
	public $ended = 0;
	public $last = 0;
	public $settings = array();
	public $progress = array();
	public $log = array();

	public function __construct() {
		add_action( 'demopress_settings_loaded', array( $this, 'prepare' ) );
		add_action( 'demopress_run_generator', array( $this, 'run' ) );
	}

	/** @return \Dev4Press\Plugin\DemoPress\Basic\Generator */
	public static function instance() {
		static $_demopress_gen = false;

		if ( ! $_demopress_gen ) {
			$_demopress_gen = new Generator();
		}

		return $_demopress_gen;
	}

	public function generator() {
		return $this->_generator;
	}

	public function timeout() {
		return $this->_timeout;
	}

	public function prepare() {
		if ( ! $this->_init ) {
			$settings = demopress_settings()->group_get( 'task' );

			foreach ( $settings as $key => $data ) {
				$this->$key = $data;
			}

			$stop = demopress_settings()->get( 'stop', 'ctrl' );

			if ( $stop ) {
				$this->status = 'finished';

				$this->save();

				demopress_settings()->set( 'stop', false, 'ctrl', true );
			}

			$this->_init = true;
		}
	}

	public function save( $progress = false ) {
		if ( $progress ) {
			demopress_settings()->set( 'progress', $this->progress, 'task', true );
		} else {
			$settings = array_keys( demopress_settings()->group_get( 'task' ) );

			foreach ( $settings as $key ) {
				demopress_settings()->set( $key, $this->$key, 'task' );
			}

			demopress_settings()->save( 'task' );
		}
	}

	public function schedule_next() {
		wp_schedule_single_event( time() + 5, 'demopress_run_generator' );
	}

	public function new_task( $type, $settings ) {
		$this->status   = 'running';
		$this->started  = microtime( true );
		$this->ended    = 0;
		$this->last     = 0;
		$this->type     = $type;
		$this->settings = $settings;
		$this->progress = array();
		$this->log      = array();

		$this->save();

		demopress_settings()->set( 'stop', false, 'ctrl', true );

		$this->add_log_entry( __( "New generator task is added.", "demopress" ), true );

		$this->schedule_next();
	}

	public function stop_task() {
		demopress_settings()->set( 'stop', true, 'ctrl', true );
	}

	public function reset_task() {
		$this->status   = 'idle';
		$this->started  = 0;
		$this->ended    = 0;
		$this->last     = 0;
		$this->type     = '';
		$this->settings = array();
		$this->progress = array();
		$this->log      = array();

		$this->save();

		demopress_settings()->set( 'stop', false, 'ctrl', true );
	}

	public function check_health() {
		if ( $this->is_running() ) {
			$gone_away = apply_filters( 'demopress_generator_run_gone_away_cutoff', 120 );

			if ( $this->last > 0 && $this->last + $gone_away < microtime( true ) ) {
				$this->add_log_entry( __( "Generator has stalled, trying to recover it...", "demopress" ), true );

				$this->run();
			}
		}
	}

	public function change_status( $status ) {
		$this->status = $status;

		if ( $status == 'error' || $status == 'finished' ) {
			$this->ended = microtime( true );

			$this->add_log_entry( __( "The generator task has finished.", "demopress" ), true );
		}

		$this->save();
	}

	public function is_running() {
		return $this->status == 'running';
	}

	public function is_finished() {
		return $this->status == 'finished';
	}

	public function is_idle() {
		return $this->status == 'idle';
	}

	public function is_error() {
		return $this->status == 'error';
	}

	public function add_log_entry( $log, $item_mark = false, $last_timestamp = false ) {
		$this->log[] = array( time(), $log, $item_mark );

		demopress_settings()->set( 'log', $this->log, 'task' );

		if ( $last_timestamp !== false && $this->last != $last_timestamp ) {
			$this->last = $last_timestamp;

			demopress_settings()->set( 'last', $this->last, 'task' );
		}

		demopress_settings()->save( 'task' );
	}

	public function format_log_list() {
		$time = '';
		$list = array();

		foreach ( $this->log as $log ) {
			$show_time = false;

			if ( $time !== $log[0] ) {
				$time      = $log[0];
				$show_time = true;
			} else if ( $log[2] ) {
				$show_time = true;
			}

			$item = '';
			if ( $show_time ) {
				$item .= date( 'Y-m-d H:i:s', $time ) . ' : ';
			} else {
				$item .= '                      ';
			}

			$item .= $log[1];

			$list[] = $item;
		}

		return $list;
	}

	public function run() {
		$this->prepare();

		if ( $this->is_running() ) {
			$this->_generator = demopress()->get_generator( $this->type );
			$this->_generator->run();
		}

		if ( $this->is_running() ) {
			$this->schedule_next();
		}
	}
}