<?php

namespace Dev4Press\Plugin\DemoPress\Generator;

use Dev4Press\v35\Core\Options\Element as EL;
use Dev4Press\v35\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WooCommerce extends Content {
	public $name = 'woocommerce';

	private $stock_status = array(
		'instock',
		'outofstock',
		'onbackorder'
	);

	public function get_list_of_types( $return = 'objects' ) : array {
		$post_types = demopress_get_woocommerce_post_types();

		return $return == 'keys' ? array_keys( $post_types ) : $post_types;
	}

	protected function init_builders() {
		$this->builders['title']    = array(
			'type' => 'title',
			'list' => demopress()->find_builders( 'title' )
		);
		$this->builders['content']  = array(
			'type' => 'html',
			'list' => demopress()->find_builders( 'html' )
		);
		$this->builders['excerpt']  = array(
			'type' => 'text',
			'list' => demopress()->find_builders( 'text' )
		);
		$this->builders['featured'] = array(
			'type' => 'image',
			'list' => demopress()->find_builders( 'image', array( 'local' ) )
		);
	}

	protected function pre_sections( $sections, $type ) {
		$sections[] = array(
			'label'    => __( "Product Properties", "demopress" ),
			'name'     => '',
			'class'    => '',
			'settings' => array(
				EL::i( $this->name, $type . '-base-woocommerce-type', __( "Type", "demopress" ), __( "If download is enabled, the download file will be to the Featured image, if the image is enabled.", "demopress" ), Type::SELECT, 'virtual_download' )->data( 'array', array(
					'physical'          => __( "Physical", "demopress" ),
					'physical_download' => __( "Physical with Download", "demopress" ),
					'virtual'           => __( "Virtual", "demopress" ),
					'virtual_download'  => __( "Virtual with Download", "demopress" )
				) ),
				EL::i( $this->name, $type . '-base-woocommerce-stock', __( "Stock", "demopress" ), '', Type::SELECT, 'instock' )->data( 'array', array(
					'RND'         => __( "Random", "demopress" ),
					'instock'     => __( "In Stock", "demopress" ),
					'outofstock'  => __( "Out Of Stock", "demopress" ),
					'onbackorder' => __( "On Backorder", "demopress" )
				) ),
				EL::i( $this->name, $type . '-base-woocommerce-sku', __( "SKU Number", "demopress" ), '', Type::SELECT, 'ean' )->data( 'array', array(
					'none' => __( "No", "demopress" ),
					'ean'  => __( "Random EAN", "demopress" )
				) )
			)
		);

		$sections[] = array(
			'label'    => __( "Product Price", "demopress" ),
			'name'     => '',
			'class'    => '',
			'settings' => array(
				EL::i( $this->name, $type . '-base-woocommerce-price', __( "Range", "demopress" ), __( "Random price in the specified range. Value of 0 will represent free products.", "demopress" ), Type::RANGE_ABSINT, '0=>60' ),
				EL::i( $this->name, $type . '-base-woocommerce-discount', __( "Discount", "demopress" ), '', Type::SELECT, 'no' )->data( 'array', array(
					'none' => __( "No", "demopress" ),
					'yes'  => __( "Yes", "demopress" )
				) )
			)
		);

		return $sections;
	}

	protected function generate_item( $type ) {
		$post_id = $this->_item_post( $type );

		if ( $post_id !== false ) {
			$woo = $this->get_from_base( $type, 'woocommerce', false, array() );

			$type     = $woo['type'];
			$stock    = $woo['stock'];
			$sku      = $woo['sku'];
			$price    = $woo['price'];
			$discount = $woo['discount'];

			$_meta = array(
				'_virtual'           => 'no',
				'_downloadable'      => 'no',
				'_stock_status'      => $stock != 'rnd' ? $stock : '',
				'_sku'               => '',
				'_price'             => 0,
				'_sale_price'        => 0,
				'_regular_price'     => 0,
				'_wc_average_rating' => 0,
				'_wc_review_count'   => 0,
				'_sold_individually' => 'no',
				'_backorders'        => 'no',
				'_manage_stock'      => 'no',
				'_tax_status'        => 'taxable',
				'_tax_class'         => '',
				'total_sales'        => 0
			);

			switch ( $type ) {
				case 'physical_download':
					$_meta['_downloadable'] = 'yes';
					break;
				case 'virtual':
					$_meta['_virtual'] = 'yes';
					break;
				case 'virtual_download':
					$_meta['_downloadable'] = 'yes';
					$_meta['_virtual']      = 'yes';
					break;
			}

			if ( $_meta['_downloadable'] = 'yes' ) {
				$_meta['_download_limit']  = 1;
				$_meta['_download_expiry'] = 1;

				$_image = get_the_post_thumbnail_url( $post_id, 'full' );

				if ( $_image ) {
					$_uuid                        = wp_generate_uuid4();
					$_meta['_downloadable_files'] = array(
						$_uuid => array(
							'id'   => $_uuid,
							'name' => 'Image',
							'file' => $_image
						)
					);
				}
			}

			if ( $stock == 'rnd' ) {
				$_meta['_stock_status'] = $this->stock_status[ array_rand( $this->stock_status ) ];
			}

			if ( $sku != 'no' ) {
				$_meta['_sku'] = $this->generate_sku_code();
			}

			$range = explode( '=>', $price );
			$range = array_map( 'absint', $range );

			if ( $range[0] == $range[1] ) {
				$_meta['_regular_price'] = $range[0];
			} else {
				$_meta['_regular_price'] = rand( $range[0], $range[1] );
			}

			if ( $_meta['_regular_price'] > 0 && $discount == 'yes' ) {
				$discount = rand( 10, 50 );
				$factor   = ( 100 - $discount ) / 100;

				$_meta['_price']      = absint( $_meta['_regular_price'] * $factor );
				$_meta['_sale_price'] = $_meta['_price'];
			} else {
				$_meta['_price'] = $_meta['_regular_price'];
			}

			foreach ( $_meta as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}
		}
	}

	public function generate_sku_code() : string {
		$time = time();

		$code   = '20' . str_pad( $time, 10, '0' );
		$weight = true;
		$sum    = 0;

		for ( $i = strlen( $code ) - 1; $i >= 0; $i -- ) {
			$sum    += (int) $code[ $i ] * ( $weight ? 3 : 1 );
			$weight = ! $weight;
		}

		$code .= ( 10 - ( $sum % 10 ) ) % 10;

		return $code;
	}
}