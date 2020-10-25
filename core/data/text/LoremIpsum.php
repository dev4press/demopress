<?php

namespace Dev4Press\Plugin\DemoPress\Data\Text;

use Dev4Press\Plugin\DemoPress\Library\LoremIpsum as LibLoremIpsum;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Base {
	public $name = 'loremipsum';

	public function run( $settings = array() ) {
		$defaults = array(
			'paragraphs' => 2,
			'content'    => 'html'
		);

		$settings = wp_parse_args( $settings, $defaults );

		return LibLoremIpsum::instance()->paragraphs( $settings['paragraphs'], false, false );
	}
}
