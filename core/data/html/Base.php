<?php

namespace Dev4Press\Plugin\DemoPress\Data\HTML;

use Dev4Press\Plugin\DemoPress\Builder\HTML;
use Dev4Press\v35\Core\Options\Element as EL;
use Dev4Press\v35\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base extends HTML {
	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $this->el_option_name( $type, $name, 'paragraphs' ), __( "Number of paragraphs", "demopress" ), __( "The term will have 1 or more words, up to the limit.", "demopress" ), Type::ABSINT, 3 )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'length' ), __( "Paragraph Length", "demopress" ), '', Type::SELECT, 'short' )->data( 'array', array(
				'short'  => __( "Short", "demopress" ),
				'medium' => __( "Medium", "demopress" ),
				'long'   => __( "Long", "demopress" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'html' ), __( "HTML Settings", "demopress" ), '', Type::CHECKBOXES, array(
				'decorate',
				'link',
				'headers'
			) )->data( 'array', array(
				'decorate' => __( "Bold, italic", "demopress" ),
				'link'     => __( "Links", "demopress" ),
				'ul'       => __( "Unordered lists", "demopress" ),
				'ol'       => __( "Ordered lists", "demopress" ),
				'dl'       => __( "Description lists", "demopress" ),
				'bq'       => __( "Blockquotes", "demopress" ),
				'code'     => __( "Code", "demopress" ),
				'headers'  => __( "Headers", "demopress" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) )
		);
	}
}