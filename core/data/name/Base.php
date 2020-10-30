<?php

namespace Dev4Press\Plugin\DemoPress\Data\Name;

use Dev4Press\Plugin\DemoPress\Builder\Name;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base extends Name {
	public $scope = 'local';

	public function run( $settings = array() ) {
		return ucwords( $this->data() );
	}

	abstract protected function data();
}
