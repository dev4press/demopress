<?php

namespace Dev4Press\Plugin\DemoPress\Data\Name;

use Dev4Press\Plugin\DemoPress\Builder\Name;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base extends Name {
	public $scope = 'local';

	/**
	 * @param array                                      $settings
	 * @param \Dev4Press\Plugin\DemoPress\Base\Generator $generator
	 *
	 * @return mixed
	 */
	public function run( $settings = array(), $generator = null ) {
		return ucwords( $this->data() );
	}

	abstract protected function data();
}
