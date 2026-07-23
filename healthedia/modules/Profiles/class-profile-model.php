<?php
class Healthedia_Profile_Model {
	public function track_views() {
		if ( get_query_var('healthedia_profile') ) {
			$user_id = get_query_var('healthedia_profile');
			if ( is_numeric($user_id) && get_userdata($user_id) ) {
				global $wpdb;
				$table = $wpdb->prefix . 'healthedia_metrics';

				$existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table WHERE object_id = %d AND object_type = 'user'", $user_id ) );
				if ( $existing ) {
					$wpdb->query( $wpdb->prepare( "UPDATE $table SET views = views + 1 WHERE id = %d", $existing ) );
				} else {
					$wpdb->insert( $table, array(
						'object_id' => $user_id,
						'object_type' => 'user',
						'views' => 1
					) );
				}
			}
		}
	}

	public static function get_metrics( $user_id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'healthedia_metrics';
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE object_id = %d AND object_type = 'user'", $user_id ) );
		return $row ? $row : (object) array('views' => 0, 'citations' => 0);
	}
}
