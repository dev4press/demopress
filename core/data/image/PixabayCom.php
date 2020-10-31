<?php

namespace Dev4Press\Plugin\DemoPress\Data\Image;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Core\Options\Type;
use Dev4Press\Plugin\DemoPress\Library\Pixabay;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PixabayCom extends Base {
	public $name = 'pixabaycom';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::i( $base, $this->el_option_name( $type, $name, 'category' ), __( "Category", "demopress" ), '', Type::SELECT, '' )->data( 'array', array(
				''               => __( "All", "demopress" ),
				'backgrounds'    => __( "Backgrounds", "demopress" ),
				'fashion'        => __( "Fashion", "demopress" ),
				'nature'         => __( "Nature", "demopress" ),
				'science'        => __( "Science", "demopress" ),
				'education'      => __( "Education", "demopress" ),
				'feelings'       => __( "Feelings", "demopress" ),
				'health'         => __( "Health", "demopress" ),
				'people'         => __( "Poeple", "demopress" ),
				'religion'       => __( "Religion", "demopress" ),
				'places'         => __( "Places", "demopress" ),
				'animals'        => __( "Animals", "demopress" ),
				'industry'       => __( "Indistry", "demopress" ),
				'computer'       => __( "Computer", "demopress" ),
				'food'           => __( "Food", "demopress" ),
				'sports'         => __( "Sports", "demopress" ),
				'transportation' => __( "Transportation", "demopress" ),
				'travel'         => __( "Travel", "demopress" ),
				'buildings'      => __( "Buildings", "demopress" ),
				'business'       => __( "Business", "demopress" ),
				'music'          => __( "Music", "demopress" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'colors' ), __( "Colors", "demopress" ), '', Type::CHECKBOXES, array() )->data( 'array', array(
				'grayscale'   => __( "Grayscale", "demopress" ),
				'transparent' => __( "Transparent", "demopress" ),
				'red'         => __( "Red", "demopress" ),
				'orange'      => __( "Orange", "demopress" ),
				'yellow'      => __( "Yellow", "demopress" ),
				'green'       => __( "Green", "demopress" ),
				'turquoise'   => __( "Turquoise", "demopress" ),
				'blue'        => __( "Blue", "demopress" ),
				'lilac'       => __( "Lilac", "demopress" ),
				'pink'        => __( "Pink", "demopress" ),
				'white'       => __( "White", "demopress" ),
				'gray'        => __( "Gray", "demopress" ),
				'black'       => __( "Black", "demopress" ),
				'brown'       => __( "Brown", "demopress" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'query' ), __( "Search query", "demopress" ), '', Type::TEXT, 'wordpress' )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'orientation' ), __( "Orientation", "demopress" ), '', Type::SELECT, 'all' )->data( 'array', array(
				'all'        => __( "Any", "demopress" ),
				'horizontal' => __( "Horizontal", "demopress" ),
				'vertical'   => __( "Vertical", "demopress" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) ),
			EL::i( $base, $this->el_option_name( $type, $name, 'size' ), __( "Size", "demopress" ), __( "You can not choose exact image size. To get Full HD and Original sizes, your Pixabay account has to be approved for full access.", "demopress" ), Type::SELECT, 'large' )->data( 'array', array(
				'large'    => __( "Large (1280x1280 max)", "demopress" ),
				'web'      => __( "Web (960x720 max)", "demopress" ),
				'fullhd'   => __( "Full HD (1920x1920 max)", "demopress" ),
				'original' => __( "Original", "demopress" )
			) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden ),
				'min'           => 1
			) )
		);
	}

	public function run( $settings = array() ) {
		$args = array(
			'image_type' => 'photo',
			'q'          => isset( $settings['query'] ) ? $settings['query'] : '',
			'size'       => isset( $settings['size'] ) ? $settings['size'] : 'large'
		);

		if ( isset( $settings['orientation'] ) && $settings['orientation'] != 'all' ) {
			$args['orientation'] = $settings['orientation'];
		}

		if ( isset( $settings['colors'] ) && ! empty( $settings['colors'] ) ) {
			$args['colors'] = join( ',', (array) $settings['colors'] );
		}

		if ( isset( $settings['category'] ) && ! empty( $settings['category'] ) ) {
			$args['category'] = $settings['category'];
		}

		return Pixabay::instance()->image( $args );
	}
}
