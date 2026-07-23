<?php
class Healthedia_Profile_Endpoints {
	public function register_routes() {
		register_rest_route('healthedia/v1', '/profile', array(
			'methods' => 'POST',
			'callback' => array($this, 'update_profile'),
			'permission_callback' => function () {
				return is_user_logged_in();
			}
		));
	}

	public function update_profile(WP_REST_Request $request) {
		$user_id = get_current_user_id();

		$params = $request->get_json_params();
		if (empty($params)) {
			$params = $request->get_params();
		}

		$updatable_meta = array(
			'_healthedia_specialty',
			'_healthedia_institution',
			'_healthedia_country',
			'_healthedia_orcid',
			'_healthedia_privacy_mode'
		);

		foreach ($updatable_meta as $key) {
			if (isset($params[$key])) {
				update_user_meta($user_id, $key, sanitize_text_field($params[$key]));
			}
		}

		$user_data = array('ID' => $user_id);

		if (isset($params['first_name'])) {
			$user_data['first_name'] = sanitize_text_field($params['first_name']);
			update_user_meta($user_id, 'first_name', $user_data['first_name']);
		}
		if (isset($params['last_name'])) {
			$user_data['last_name'] = sanitize_text_field($params['last_name']);
			update_user_meta($user_id, 'last_name', $user_data['last_name']);
		}

		if (isset($params['first_name']) && isset($params['last_name'])) {
			$user_data['display_name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
		}

		if (isset($params['description'])) {
			$user_data['description'] = sanitize_textarea_field($params['description']);
		}

		wp_update_user($user_data);

		if (isset($params['_healthedia_username'])) {
			$new_username = sanitize_title($params['_healthedia_username']);
			// Check if username is already taken by someone else
			$exists = get_users(array(
				'meta_key' => '_healthedia_username',
				'meta_value' => $new_username,
				'exclude' => array($user_id),
				'number' => 1
			));

			$user_by_login = get_user_by('login', $new_username);
			$login_conflict = $user_by_login && $user_by_login->ID != $user_id;

			$user_by_slug = get_user_by('slug', $new_username);
			$slug_conflict = $user_by_slug && $user_by_slug->ID != $user_id;

			if (empty($exists) && !$login_conflict && !$slug_conflict) {
				update_user_meta($user_id, '_healthedia_username', $new_username);
			} else {
				return new WP_Error('username_exists', 'That username is already taken.', array('status' => 400));
			}
		}

		return rest_ensure_response(array('success' => true, 'message' => 'Profile updated successfully.'));
	}
}
