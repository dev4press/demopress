<?php

namespace Dev4Press\Plugin\DEMOPRESS\Data\Text;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DEMOPRESS\Builder\Text;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LorIpsumNet extends Text {
	public $name = 'loripsumnet';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $type . '-builder-' . $name . '-' . $this->name . '-paragraphs', __( "Number of paragraphs", "demopress" ), __( "The term will have 1 or more words, up to the limit.", "demopress" ), Type::ABSINT, 3 )->args( array(
				'class' => $class . '-' . $this->name,
				'min'   => 1
			) ),
			EL::i( $base, $type . '-builder-' . $name . '-' . $this->name . '-length', __( "Paragraph Length" ), '', Type::SELECT, 'short' )->data( 'array', array(
				'short'  => __( "Short" ),
				'medium' => __( "Medium" ),
				'long'   => __( "Long" )
			) ),
			EL::i( $base, $type . '-builder-' . $name . '-' . $this->name . '-content', __( "Content Type" ), '', Type::SELECT, 'html' )->data( 'array', array(
				'html'      => __( "HTML" ),
				'plaintext' => __( "Plaintext" )
			) ),
			EL::i( $base, $type . '-builder-' . $name . '-' . $this->name . '-html', __( "HTML Settings" ), '', Type::CHECKBOXES, array(
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
				'code'     => __( "Code samples" ),
				'headers'  => __( "Headers" )
			) ),
			EL::i( $base, $type . '-builder-' . $name . '-' . $this->name . '-more', __( "More Settings" ), '', Type::CHECKBOXES, array() )->data( 'array', array(
				'allcaps' => __( "All caps" ),
				'prude'   => __( "Prude" )
			) )
		);
	}

	public function run( $settings = array() ) {

	}
}
