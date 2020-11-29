<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\Core\Plugins\DBLite;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DB extends DBLite {
	public function get_attachments_for_cleanup( $post_type, $return_counts = false ) {
		$ret = $return_counts ? 'COUNT(*)' : 'DISTINCT(m.post_id)';

		$sql = $this->prepare( "SELECT " . $ret . " FROM " . $this->wpdb()->postmeta . " m
			INNER JOIN " . $this->wpdb()->posts . " a ON a.ID = m.post_id AND a.post_type = 'attachment'
			INNER JOIN " . $this->wpdb()->posts . " p ON p.ID = a.post_parent AND p.post_type = %s
			WHERE m.meta_key = '_demopress_generated_content'", $post_type );

		if ( $return_counts ) {
			return $this->get_var( $sql );
		} else {
			$raw = $this->get_results( $sql );

			return wp_list_pluck( $raw, 'post_id' );
		}
	}

	public function get_posts_for_cleanup( $post_type, $return_counts = false ) {
		$ret = $return_counts ? 'COUNT(*)' : 'DISTINCT(m.post_id)';

		$sql = $this->prepare( "SELECT " . $ret . " FROM " . $this->wpdb()->postmeta . " m INNER JOIN " . $this->wpdb()->posts . " p ON p.ID = m.post_id WHERE p.post_type = %s AND m.meta_key = '_demopress_generated_content'", $post_type );

		if ( $return_counts ) {
			return $this->get_var( $sql );
		} else {
			$raw = $this->get_results( $sql );

			return wp_list_pluck( $raw, 'post_id' );
		}
	}

	public function run_posts_cleanup( $ids ) {
		$sql = "DELETE m, p FROM " . $this->wpdb()->posts . " p INNER JOIN " . $this->wpdb()->postmeta . " m ON p.ID = m.post_id WHERE p.ID IN (" . join( ',', $ids ) . ")";

		$this->query( $sql );
	}

	public function get_terms_for_cleanup( $taxonomy, $return_counts = false ) {
		$ret = $return_counts ? 'COUNT(*)' : 'DISTINCT(m.term_id)';

		$sql = $this->prepare( "SELECT " . $ret . " FROM " . $this->wpdb()->termmeta . " m 
									 INNER JOIN " . $this->wpdb()->terms . " t ON t.term_id = m.term_id
									 INNER JOIN " . $this->wpdb()->term_taxonomy . " tt ON tt.term_id = t.term_id AND tt.taxonomy = '%s'
									 WHERE m.meta_key = '_demopress_generated_content'", $taxonomy );

		if ( $return_counts ) {
			return $this->get_var( $sql );
		} else {
			$raw = $this->get_results( $sql );

			return wp_list_pluck( $raw, 'term_id' );
		}
	}

	public function run_terms_cleanup( $ids ) {
		$sql = "DELETE t, tt, m, tr FROM " . $this->wpdb()->terms . " t 
				INNER JOIN " . $this->wpdb()->term_taxonomy . " tt ON tt.term_id = t.term_id
				LEFT JOIN " . $this->wpdb()->termmeta . " m ON m.term_id = t.term_id
				LEFT JOIN " . $this->wpdb()->term_relationships . " tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
				WHERE t.term_id IN (" . join( ',', $ids ) . ")";

		$this->query( $sql );
	}

	public function get_users_for_cleanup( $return_counts = false ) {
		$ret = $return_counts ? 'COUNT(*)' : 'DISTINCT(m.user_id)';

		$sql = "SELECT " . $ret . " FROM " . $this->wpdb()->usermeta . " m INNER JOIN " . $this->wpdb()->users . " u ON u.ID = m.user_id WHERE m.meta_key = '_demopress_generated_content'";

		if ( $return_counts ) {
			return $this->get_var( $sql );
		} else {
			$raw = $this->get_results( $sql );

			return wp_list_pluck( $raw, 'user_id' );
		}
	}

	public function run_users_cleanup( $ids ) {
		$sql = "DELETE u, m FROM " . $this->wpdb()->users . " u LEFT JOIN " . $this->wpdb()->usermeta . " m ON u.ID = m.user_id WHERE u.ID IN (" . join( ',', $ids ) . ")";

		$this->query( $sql );
	}

	public function get_comments_for_cleanup( $post_type, $comment_type, $return_counts = false ) {
		$ret = $return_counts ? 'COUNT(*)' : 'DISTINCT(m.comment_id)';

		$sql = $this->prepare( "SELECT " . $ret . " FROM " . $this->wpdb()->commentmeta . " m 
									 INNER JOIN " . $this->wpdb()->comments . " c ON c.comment_ID = m.comment_id AND c.comment_type = %s
									 INNER JOIN " . $this->wpdb()->posts . " p ON p.ID = c.comment_post_ID AND p.post_type = %s
									 WHERE m.meta_key = '_demopress_generated_content'", $comment_type, $post_type );

		if ( $return_counts ) {
			return $this->get_var( $sql );
		} else {
			$raw = $this->get_results( $sql );

			return wp_list_pluck( $raw, 'comment_id' );
		}
	}

	public function run_comments_cleanup( $ids ) {
		$sql = "DELETE c, m FROM " . $this->wpdb()->comments . " c LEFT JOIN " . $this->wpdb()->commentmeta . " m ON c.comment_ID = m.comment_id WHERE c.comment_ID IN (" . join( ',', $ids ) . ")";

		$this->query( $sql );
	}

	public function get_topics_for_forums( $forums ) {
		$sql = $this->prepare( "SELECT p.ID, p.post_parent, p.post_date FROM " . $this->wpdb()->posts . " p WHERE p.post_status = 'publish' AND p.post_type = %s", bbp_get_topic_post_type() );
		$sql .= ' AND p.post_parent IN (' . join( ',', $forums ) . ')';

		return $this->get_results( $sql );
	}

	public function get_posts_for_post_type( $post_type ) {
		$sql = $this->prepare( "SELECT p.ID FROM " . $this->wpdb()->posts . " p WHERE p.post_status = 'publish' AND p.post_type = %s", $post_type );

		return $this->get_results( $sql );
	}

	public function get_posts_for_comments( $post_type, $include = array(), $exclude = array() ) {
		$sql = $this->prepare( "SELECT p.ID, p.post_date FROM " . $this->wpdb()->posts . " p WHERE p.post_status = 'publish' AND p.post_type = %s", $post_type );

		if ( ! empty( $include ) ) {
			$sql .= ' AND p.ID IN (' . join( ',', $include ) . ')';
		} else if ( ! empty( $exclude ) ) {
			$sql .= ' AND p.ID NOT IN (' . join( ',', $exclude ) . ')';
		}

		return $this->get_results( $sql );
	}

	public function get_comments_for_post( $post_id ) {
		$sql = $this->prepare( "SELECT c.comment_ID, c.comment_date FROM " . $this->wpdb()->comments . " c WHERE c.comment_post_ID = %d AND c.comment_approved = '1' ORDER by c.comment_date ASC", $post_id );

		return $this->get_results( $sql );
	}

	public function get_titles_for_post_types( $post_type ) {
		$sql = $this->prepare( "SELECT post_title FROM " . $this->wpdb()->posts . " WHERE post_type = %s AND post_status = 'publish'", $post_type );
		$raw = $this->get_results( $sql );

		return wp_list_pluck( $raw, 'post_title' );
	}

	public function check_if_image_exists( $name ) {
		$sql = $this->prepare( "SELECT count(*) FROM " . $this->wpdb()->posts . " WHERE post_name = %s", $name );

		return $this->get_var( $sql ) > 0;
	}

	public function check_if_title_exists( $post_type, $title ) {
		$sql = $this->prepare( "SELECT count(*) FROM " . $this->wpdb()->posts . " WHERE post_title = %s AND post_type = %s AND post_status = 'publish'", $title, $post_type );

		return $this->get_var( $sql ) > 0;
	}
}
