<?php

namespace Dev4Press\Plugin\DemoPress\Exception;

use Exception;

class Builder extends Exception {
	protected $type;
	protected $builder;

	public function __construct( $code, $message, $type, $builder ) {
		parent::__construct( $message, $code );

		$this->type    = $type;
		$this->builder = $builder;
	}

	final public function getType() {
		return $this->type;
	}

	final public function getBuilder() {
		return $this->builder;
	}
}