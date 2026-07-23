<?php
class Healthedia_Search_Endpoints {
	public function register_routes() {
		register_rest_route('healthedia/v1', '/search', array(
			'methods' => 'GET',
			'callback' => array($this, 'do_search'),
			'permission_callback' => '__return_true'
		));
	}

	public function do_search($request) {
		global $wpdb;
		$query = sanitize_text_field($request->get_param('q'));
		$type = sanitize_text_field($request->get_param('type')); // e.g., 'healthedia_article', 'user'

		if (empty($query)) {
			return rest_ensure_response(array('results' => []));
		}

		$table = $wpdb->prefix . 'healthedia_search_index';

		$args = array($query . '*');
		$sql = "SELECT object_id, object_type, title, metadata FROM $table WHERE MATCH(title, content) AGAINST(%s IN BOOLEAN MODE)";

		if (!empty($type)) {
			$sql .= " AND object_type = %s";
			$args[] = $type;
		}

		$sql .= " LIMIT 20";

		$results = $wpdb->get_results($wpdb->prepare($sql, $args));

		// Map metadata JSON back to array for API response
		foreach ($results as &$row) {
			$row->metadata = json_decode($row->metadata, true);
			if ($row->object_type === 'user') {
				$row->url = home_url('/profile/' . $row->object_id);
			} else {
				$row->url = get_permalink($row->object_id);
			}
		}

		return rest_ensure_response(array('results' => $results));
	}
}
