<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\Core\Plugins\DBLite;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DB extends DBLite {
	public function get_topics_for_forums( $forums ) {
		$sql = $this->prepare( "SELECT p.ID, p.post_parent, p.post_date FROM " . $this->wpdb()->posts . " p WHERE p.post_status = 'publish' AND p.post_type = %s", bbp_get_topic_post_type() );
		$sql .= ' AND p.post_parent IN (' . join( ',', $forums ) . ')';

		return $this->get_results( $sql );
	}

	public function get_posts_for_post_type( $post_type ) {
		$sql = $this->prepare( "SELECT p.ID FROM " . $this->wpdb()->posts . " p WHERE p.post_status = 'publish' AND p.post_type = %s", $post_type );

		return $this->get_results( $sql );
	}

	public function get_posts_for_comments( $post_type, $exclude = array() ) {
		$sql = $this->prepare( "SELECT p.ID, p.post_date FROM " . $this->wpdb()->posts . " p WHERE p.post_status = 'publish' AND p.post_type = %s", $post_type );

		if ( ! empty( $exclude ) ) {
			$sql .= ' AND p.ID NOT IN (' . join( ',', $exclude ) . ')';
		}

		return $this->get_results( $sql );
	}

	public function get_comments_for_post( $post_id ) {
		$sql = $this->prepare( "SELECT c.comment_ID, c.comment_date FROM " . $this->wpdb()->comments . " c WHERE c.comment_post_ID = %d ORDER by c.comment_date ASC", $post_id );

		return $this->get_results( $sql );
	}

	public function check_if_image_exists( $name ) {
		$sql = $this->prepare( "SELECT count(*) FROM " . $this->wpdb()->posts . " WHERE post_name = %s", $name );

		return $this->get_var( $sql ) > 0;
	}
}
