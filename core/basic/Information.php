<?php

namespace Dev4Press\Plugin\DEMOPRESS\Basic;

use Dev4Press\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'demopress';

	public $version = '1.0';
	public $build = 10;
	public $updated = '2020.12.02';
	public $status = 'stable';
	public $edition = 'pro';
	public $released = '2020.12.02';

	public $php = '7.0';
}
