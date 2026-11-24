<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\v56\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public string $code = 'demopress';

	public string $version = '3.0';
	public int $build = 300;
	public string $updated = '2026.12.01';
	public string $status = 'stable';
	public string $edition = 'free';
	public string $released = '2020.11.17';

	public string $github_url = 'https://github.com/dev4press/demopress';
	public string $wp_org_url = 'https://wordpress.org/plugins/demopress';
}
