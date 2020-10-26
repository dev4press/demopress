<?php

namespace Dev4Press\Plugin\DemoPress\Data\HTML;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Builder\HTML;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base extends HTML {
	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $this->el_option_name( $type, $name, 'content' ), __( "Content Type" ), '', Type::SELECT, 'html' )->data( 'array', array(
				'html'      => __( "HTML" ),
				'plaintext' => __( "Plaintext" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'html' ), __( "HTML Settings" ), '', Type::CHECKBOXES, array(
				'decorate',
				'link',
				'headers'
			) )->data( 'array', array(
				'decorate' => __( "Bold, italic" ),
				'link'     => __( "Links" ),
				'ul'       => __( "Unordered lists" ),
				'ol'       => __( "Ordered lists" ),
				'dl'       => __( "Description lists" ),
				'bq'       => __( "Blockquotes" ),
				'code'     => __( "Code" ),
				'headers'  => __( "Headers" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'paragraphs' ), __( "Number of paragraphs", "demopress" ), __( "The term will have 1 or more words, up to the limit.", "demopress" ), Type::ABSINT, 3 )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) )
		);
	}
}