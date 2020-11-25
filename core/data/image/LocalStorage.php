<?php

namespace Dev4Press\Plugin\DemoPress\Data\Image;

use Dev4Press\Core\Options\Element as EL;
use Dev4Press\Plugin\DemoPress\Builder\Image;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LocalStorage extends Image {
	public $name = 'localstorage';
	public $scope = 'local';

	public function settings( $base, $type, $name, $class, $hidden = false ) {
		return array(
			EL::info( __( "Storage Path", "demopress" ), sprintf( __( "Images must be inside this directory: %s", "demopress" ), '<br/><code>' . $this->directory_path( $type ) . '</code>' ) )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) ),
			EL::info( __( "Available Images", "demopress" ), sprintf( __( "Plugin has found %s images. If you set the generator to more posts than images available, not every post will have a featured image. Images will be assigned in the order they are found in the directory.", "demopress" ), '<strong>' . $this->get_images( $type, 'counts' ) . '</strong>' ) . '<br/><strong>' . __( "Whenever images are used, the plugin will move images from the storage directory to final destination in the media library!", "demopress" ) . '</strong>' )->args( array(
				'wrapper_class' => $this->el_wrapper_class( $class, $hidden )
			) )
		);
	}

	private function directory_path( $type ) {
		$dir = wp_upload_dir();
		$dir = str_replace( ABSPATH, '', $dir['basedir'] );

		return trailingslashit( str_replace( '\\', '/', $dir ) ) . 'demopress/' . $type . '/';
	}

	private function get_images( $type, $return = 'files' ) {
		$path  = ABSPATH . $this->directory_path( $type );
		$files = d4p_scan_dir( $path, 'file', array( 'png', 'jpg', 'jpeg', 'webp', 'gif' ) );

		return $return == 'counts' ? count( $files ) : $files;
	}

	/**
	 * @param array                                      $settings
	 * @param \Dev4Press\Plugin\DemoPress\Base\Generator $generator
	 *
	 * @return mixed
	 */
	public function run( $settings = array(), $generator = null ) {
		$type  = demopress_get_active_generator()->current_type();
		$root  = ABSPATH . $this->directory_path( $type );
		$files = $this->get_images( $type );

		if ( ! empty( $files ) ) {
			return array(
				'path' => $root . $files[0],
				'data' => array()
			);
		}

		return false;
	}
}
