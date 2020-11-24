<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'demopress';

	public $version = '1.1';
	public $build = 15;
	public $updated = '2020.11.25';
	public $status = 'stable';
	public $edition = 'pro';
	public $released = '2020.11.17';

	public $php = '7.0';
}
