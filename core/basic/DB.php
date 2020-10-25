<?php

namespace Dev4Press\Plugin\DemoPress\Basic;

use Dev4Press\Core\Plugins\DBLite;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DB extends DBLite {
	public function get_terms_for_taxonomy( $taxonomy ) {
		$sql = $this->prepare( "SELECT t.term_id, t.slug FROM " . $this->wpdb()->terms . " t INNER JOIN " . $this->wpdb()->term_taxonomy . " tt ON tt.term_id = t.term_id WHERE tt.taxonomy = %s", $taxonomy );

		return $this->get_results( $sql );
	}
}
