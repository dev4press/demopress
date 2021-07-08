<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\v35\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'demopress';

	public $version = '1.3';
	public $build = 35;
	public $updated = '2021.07.20';
	public $status = 'stable';
	public $edition = 'pro';
	public $released = '2020.11.17';

	public $php = '7.0';

	public static function instance() : Information {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Information();
		}

		return $instance;
	}
}
