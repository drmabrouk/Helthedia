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
		$type = sanitize_text_field($request->get_param('type'));

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

		foreach ($results as &$row) {
			$raw_meta = json_decode($row->metadata, true);
			$safe_meta = array();

			// Filter to only expose explicit, safe public metadata
			if ($row->object_type === 'user') {
				if (isset($raw_meta['_healthedia_specialty'])) $safe_meta['specialty'] = $raw_meta['_healthedia_specialty'][0];
				if (isset($raw_meta['_healthedia_verified'])) $safe_meta['verified'] = $raw_meta['_healthedia_verified'][0];
				$row->url = home_url('/profile/' . $row->object_id);
			} else {
				if (isset($raw_meta['_healthedia_doi'])) $safe_meta['doi'] = $raw_meta['_healthedia_doi'][0];
				$row->url = get_permalink($row->object_id);
			}

			$row->metadata = $safe_meta; // Override with safe metadata only
		}

		return rest_ensure_response(array('results' => $results));
	}
}
