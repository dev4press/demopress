<?php

namespace Dev4Press\Plugin\DemoPress\Data\Title;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Builder\Title;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Listed extends Title {
	public $name = 'listed';

	private $cached = false;

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $this->el_option_name( $type, $name, 'list' ), __( "List of Titles", "demopress" ), __( "Provide the list of titles to use for the posts you want created. The number of posts generated must much the number of titles listed here. If you request more posts than titles provided, plugin will generate posts that have a title, and stop. Each line must contain one title!", "demopress" ), Type::LISTING, array() )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) )
		);
	}

	/**
	 * @param array                                      $settings
	 * @param \Dev4Press\Plugin\DemoPress\Base\Generator $generator
	 *
	 * @return mixed
	 */
	public function run( $settings = array(), $generator = null ) {
		$defaults = array(
			'list' => array()
		);

		$settings = wp_parse_args( $settings, $defaults );

		if ( empty( $settings['list'] ) ) {
			return new WP_Error( 'title-list-empty', __( "Titles list is empty." ) );
		}

		if ( $this->cached === false ) {
			$type         = demopress_get_active_generator()->current_type();
			$this->cached = demopress_db()->get_titles_for_post_types( $type );
		}

		$item  = 0;
		$found = false;

		while ( $found === false ) {
			$title = $settings['list'][ $item ];

			if ( ! in_array( $title, $this->cached ) ) {
				$this->cached[] = $title;

				$found = $title;
				break;
			}

			$item ++;
		}

		if ( $found === false ) {
			$found = new WP_Error( 'title-list-no-more', __( "No more titles are available in the list." ) );
		}

		return $found;
	}
}
