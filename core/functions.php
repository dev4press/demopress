<?php

use function Dev4Press\v35\Functions\bbPress\is_active;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function demopress_post_type_support_comment_type( $post_type, $comment_type ) {
	switch ( $comment_type ) {
		case 'comment':
			return post_type_supports( $post_type, 'comments' );
		default:
			return apply_filters( 'demopress_post_type_support_comment_type', false, $post_type, $comment_type );
	}
}

function demopress_get_comment_types() : array {
	return (array)apply_filters( 'demopress_get_comment_types', array(
			'comment' => (object) array(
				'name'  => 'comment',
				'label' => __( "Comments", "demopress" )
			)
		)
	);
}

function demopress_get_taxonomies() : array {
	$taxonomies = get_taxonomies( array( 'public' => true, 'show_ui' => true ), 'objects' );

	return (array)apply_filters( 'demopress_get_taxonomies', $taxonomies );
}

function demopress_get_bbpress_post_types() : array {
	$post_types = array(
		bbp_get_forum_post_type() => get_post_type_object( bbp_get_forum_post_type() ),
		bbp_get_topic_post_type() => get_post_type_object( bbp_get_topic_post_type() ),
		bbp_get_reply_post_type() => get_post_type_object( bbp_get_reply_post_type() )
	);

	return (array)apply_filters( 'demopress_get_bbpress_post_types', $post_types );
}

function demopress_get_post_types() {
	$post_types = get_post_types( array( 'public' => true, 'show_ui' => true ), 'objects' );

	if ( isset( $post_types['attachment'] ) ) {
		unset( $post_types['attachment'] );
	}

	if ( is_active() ) {
		unset( $post_types[ bbp_get_forum_post_type() ] );
		unset( $post_types[ bbp_get_topic_post_type() ] );
		unset( $post_types[ bbp_get_reply_post_type() ] );
	}

	if (demopress_is_woocommerce_active()) {
		unset($post_types['product']);
	}

	return apply_filters( 'demopress_get_post_types', $post_types );
}

function demopress_get_bbpress_forums_list() : array {
	$_base_forums = get_posts( array(
		'post_type'   => bbp_get_forum_post_type(),
		'numberposts' => - 1,
	) );

	$forums = array();

	foreach ( $_base_forums as $forum ) {
		$forums[ $forum->ID ] = (object) array(
			'id'     => $forum->ID,
			'url'    => get_permalink( $forum->ID ),
			'parent' => $forum->post_parent,
			'title'  => $forum->post_title
		);
	}

	return $forums;
}

function demopress_get_active_generator() {
	if ( demopress_gen()->is_running() ) {
		return demopress_gen()->generator();
	}

	return null;
}

function demopress_is_woocommerce_active() : bool {
	return class_exists( 'WooCommerce' ) && function_exists('WC');
}

function demopress_get_woocommerce_post_types() : array {
	$post_types = array(
		'product' => get_post_type_object( 'product' )
	);

	return (array)apply_filters( 'demopress_get_woocommerce_post_types', $post_types );
}
