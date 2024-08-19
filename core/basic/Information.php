<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\v49\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'demopress';

	public $version = '2.0';
	public $build = 200;
	public $updated = '2024.08.20';
	public $status = 'stable';
	public $edition = 'pro';
	public $released = '2020.11.17';
}
