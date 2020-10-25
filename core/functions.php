<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
