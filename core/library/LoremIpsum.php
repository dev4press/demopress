<?php

namespace Dev4Press\Plugin\DemoPress\Library;

use Dev4Press\Plugin\DemoPress\Base\Library;
use Dev4Press\v56\Generator\Text\LoremIpsum as BaseLoremIpsum;
use WP_Block_Type_Registry;
use WP_HTML_Tag_Processor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoremIpsum extends Library {
	private $_object;

	public function __construct() {
		$this->_object = new class extends BaseLoremIpsum {
			protected function gauss( $mean, $std_dev ): float {
				// Bound wp_rand() to the divisor so the logarithm receives a value in (0, 1].
				$max = mt_getrandmax();
				$x   = wp_rand( 1, $max ) / $max;
				$y   = wp_rand( 0, $max ) / $max;
				$z   = sqrt( - 2 * log( $x ) ) * cos( 2 * pi() * $y );

				return max( 1.0, $z * $std_dev + $mean );
			}
		};

		// Library 5.6.5 fails when shuffling with a protected opening phrase.
		$this->_object->protected_first();
	}

	public function words( $count ) {
		return $this->_object->words( $count );
	}

	public function change_length( $method ) {
		$this->_object->change_paragraph_gauss( $method );
	}

	public function paragraphs( $count ) {
		return $this->_object->paragraphs( $count );
	}

	public function html( $count, $settings = array(), $block_formatted = false ) {
		$html = $this->_object->html( $count, $settings, $block_formatted );

		if ( ! $block_formatted ) {
			return $html;
		}

		return implode( '', array_map( array( $this, 'format_block' ), parse_blocks( $html ) ) );
	}

	private function format_block( array $block ): string {
		if ( ! empty( $block['innerBlocks'] ) ) {
			return serialize_block( $block );
		}

		$name = $block['blockName'];
		$html = $block['innerHTML'];

		switch ( $name ) {
			case 'core/list':
				$html = $this->wrap_block_children( $html, 'li', 'core/list-item' );
				$html = $this->add_block_class( $html, $name, 'wp-block-list' );
				break;
			case 'core/quote':
				$html = $this->wrap_block_children( $html, 'p', 'core/paragraph' );
				break;
			case 'core/heading':
				$html = $this->add_block_class( $html, $name, 'wp-block-heading' );
				break;
			default:
				return serialize_block( $block );
		}

		return get_comment_delimited_block_content( $name, $block['attrs'], $html );
	}

	private function wrap_block_children( string $html, string $tag, string $block_name ): string {
		// The Lorem Ipsum generator produces flat lists and single-paragraph quotes.
		return preg_replace_callback(
			       '~<' . $tag . '>.*?</' . $tag . '>~s',
			       static fn( array $match ) => get_comment_delimited_block_content( $block_name, array(), $match[0] ),
			       $html
		       ) ?? $html;
	}

	private function add_block_class( string $html, string $name, string $class ): string {
		$type = WP_Block_Type_Registry::get_instance()->get_registered( $name );
		if ( ! block_has_support( $type, 'className', true ) ) {
			return $html;
		}

		$tags = new WP_HTML_Tag_Processor( $html );
		if ( $tags->next_tag() ) {
			$tags->add_class( $class );
		}

		return $tags->get_updated_html();
	}
}
