<?php

namespace Dev4Press\Plugin\DEMOPRESS\Data\Name;

use Dev4Press\Plugin\DEMOPRESS\Builder\Name;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base extends Name {
	public function run( $settings = array() ) {
		return ucwords( $this->data() );
	}

	abstract protected function data();
}
