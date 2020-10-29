<?php

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

function demopress_get_comment_types() {
	return apply_filters( 'demopress_get_comment_types', array(
			'comment' => (object) array(
				'label' => __( "Comment" )
			)
		)
	);
}

function demopress_get_taxonomies() {
	$taxonomies = get_taxonomies( array( 'public' => true, 'show_ui' => true ), 'objects' );

	return apply_filters( 'demopress_get_taxonomies', $taxonomies );
}

function demopress_get_post_types() {
	$post_types = get_post_types( array( 'public' => true, 'show_ui' => true ), 'objects' );

	if ( isset( $post_types['attachment'] ) ) {
		unset( $post_types['attachment'] );
	}

	if ( d4p_has_bbpress() ) {
		unset( $post_types[ bbp_get_forum_post_type() ] );
		unset( $post_types[ bbp_get_topic_post_type() ] );
		unset( $post_types[ bbp_get_reply_post_type() ] );
	}

	return apply_filters( 'demopress_get_post_types', $post_types );
}
