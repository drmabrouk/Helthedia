<?php
class Healthedia_Dashboard_API {
	public function register_routes() {
		register_rest_route('healthedia/v1', '/admin/stats', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_stats'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/users', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_users'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/users', array(
			'methods' => 'POST',
			'callback' => array($this, 'create_user'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/users/(?P<id>\d+)', array(
			'methods' => 'PUT',
			'callback' => array($this, 'update_user'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/users/(?P<id>\d+)', array(
			'methods' => 'DELETE',
			'callback' => array($this, 'delete_user'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));

		register_rest_route('healthedia/v1', '/admin/researchers', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_researchers'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/researchers', array(
			'methods' => 'POST',
			'callback' => array($this, 'create_researcher'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/researchers/(?P<id>\d+)', array(
			'methods' => 'PUT',
			'callback' => array($this, 'update_researcher'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/researchers/(?P<id>\d+)', array(
			'methods' => 'DELETE',
			'callback' => array($this, 'delete_user'), // reuse
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/wipe-mock-data', array(
			'methods' => 'POST',
			'callback' => array($this, 'wipe_mock_data'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/settings', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_settings'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
		register_rest_route('healthedia/v1', '/admin/settings', array(
			'methods' => 'POST',
			'callback' => array($this, 'save_settings'),
			'permission_callback' => array($this, 'check_admin_permissions')
		));
	}

	public function check_admin_permissions() {
		return current_user_can('manage_options');
	}

	public function get_stats() {
		global $wpdb;
		$total_users = count_users();
		$total_articles = wp_count_posts('healthedia_article')->publish;

		$metrics_table = $wpdb->prefix . 'healthedia_metrics';
		$total_views = $wpdb->get_var("SELECT SUM(views) FROM $metrics_table");

		return rest_ensure_response(array(
			'users' => $total_users['total_users'],
			'articles' => $total_articles,
			'total_views' => $total_views ?: 0
		));
	}

	public function get_users() {
		$users = get_users();
		$data = array();
		foreach ($users as $user) {
			$data[] = array(
				'id' => $user->ID,
				'email' => $user->user_email,
				'name' => $user->display_name,
				'registered' => $user->user_registered,
				'roles' => $user->roles
			);
		}
		return rest_ensure_response($data);
	}

	public function create_user(WP_REST_Request $request) {
		$params = $request->get_json_params();
		$email = sanitize_email($params['email']);
		$name = sanitize_text_field($params['name']);
		$role = sanitize_text_field($params['role'] ?? 'subscriber');

		if (empty($email) || empty($name)) {
			return new WP_Error('missing_fields', 'Name and Email are required.', array('status' => 400));
		}

		if (email_exists($email)) {
			return new WP_Error('email_exists', 'User with this email already exists.', array('status' => 400));
		}

		$user_id = wp_insert_user(array(
			'user_login' => $email,
			'user_pass' => wp_generate_password(),
			'user_email' => $email,
			'display_name' => $name,
			'role' => $role
		));

		if (is_wp_error($user_id)) {
			return new WP_Error('create_failed', $user_id->get_error_message(), array('status' => 500));
		}

		return rest_ensure_response(array('success' => true, 'id' => $user_id));
	}

	public function update_user(WP_REST_Request $request) {
		$id = $request->get_param('id');
		$params = $request->get_json_params();

		$user_data = array('ID' => $id);
		if (isset($params['name'])) $user_data['display_name'] = sanitize_text_field($params['name']);
		if (isset($params['email'])) $user_data['user_email'] = sanitize_email($params['email']);
		if (isset($params['role'])) $user_data['role'] = sanitize_text_field($params['role']);

		$user_id = wp_update_user($user_data);
		if (is_wp_error($user_id)) {
			return new WP_Error('update_failed', $user_id->get_error_message(), array('status' => 500));
		}

		return rest_ensure_response(array('success' => true));
	}

	public function delete_user(WP_REST_Request $request) {
		$id = $request->get_param('id');
		if ($id == get_current_user_id()) {
			return new WP_Error('delete_self', 'You cannot delete yourself.', array('status' => 400));
		}
		require_once(ABSPATH . 'wp-admin/includes/user.php');
		if (wp_delete_user($id)) {
			return rest_ensure_response(array('success' => true));
		}
		return new WP_Error('delete_failed', 'Failed to delete user.', array('status' => 500));
	}

	public function get_researchers() {
		$users = get_users(array(
			'meta_key' => '_healthedia_verified',
			'meta_value' => '1'
		));
		$data = array();
		foreach ($users as $user) {
			$data[] = array(
				'id' => $user->ID,
				'email' => $user->user_email,
				'name' => $user->display_name,
				'specialty' => get_user_meta($user->ID, '_healthedia_specialty', true),
				'institution' => get_user_meta($user->ID, '_healthedia_institution', true),
				'is_mock' => get_user_meta($user->ID, '_healthedia_is_mock', true) === 'yes'
			);
		}
		return rest_ensure_response($data);
	}

	public function create_researcher(WP_REST_Request $request) {
		$params = $request->get_json_params();
		$email = sanitize_email($params['email']);
		$name = sanitize_text_field($params['name']);
		$specialty = sanitize_text_field($params['specialty']);
		$institution = sanitize_text_field($params['institution']);

		if (empty($email) || empty($name)) {
			return new WP_Error('missing_fields', 'Name and Email are required.', array('status' => 400));
		}

		if (email_exists($email)) {
			return new WP_Error('email_exists', 'User with this email already exists.', array('status' => 400));
		}

		$user_id = wp_insert_user(array(
			'user_login' => $email,
			'user_pass' => wp_generate_password(),
			'user_email' => $email,
			'display_name' => $name,
			'role' => 'subscriber'
		));

		if (is_wp_error($user_id)) {
			return new WP_Error('create_failed', $user_id->get_error_message(), array('status' => 500));
		}

		update_user_meta($user_id, '_healthedia_verified', '1');
		update_user_meta($user_id, '_healthedia_specialty', $specialty);
		update_user_meta($user_id, '_healthedia_institution', $institution);

		return rest_ensure_response(array('success' => true, 'id' => $user_id));
	}

	public function update_researcher(WP_REST_Request $request) {
		$id = $request->get_param('id');
		$params = $request->get_json_params();

		$user_data = array('ID' => $id);
		if (isset($params['name'])) $user_data['display_name'] = sanitize_text_field($params['name']);
		if (isset($params['email'])) $user_data['user_email'] = sanitize_email($params['email']);

		$user_id = wp_update_user($user_data);
		if (is_wp_error($user_id)) {
			return new WP_Error('update_failed', $user_id->get_error_message(), array('status' => 500));
		}

		if (isset($params['specialty'])) update_user_meta($id, '_healthedia_specialty', sanitize_text_field($params['specialty']));
		if (isset($params['institution'])) update_user_meta($id, '_healthedia_institution', sanitize_text_field($params['institution']));

		return rest_ensure_response(array('success' => true));
	}

	public function wipe_mock_data() {
		require_once HEALTHEDIA_PLUGIN_DIR . 'includes/class-healthedia-seeder.php';
		Healthedia_Seeder::wipe_mock_data();
		return rest_ensure_response(array('success' => true, 'message' => 'Mock data wiped successfully.'));
	}

	public function get_settings() {
		return rest_ensure_response(array(
			'site_name' => get_option('blogname'),
			'site_desc' => get_option('blogdescription'),
			'admin_email' => get_option('admin_email'),
			'mock_data_seeded' => get_option('healthedia_mock_data_seeded', false),
			'enable_registration' => get_option('healthedia_enable_registration', 'yes'),
			'auth_maintenance_mode' => get_option('healthedia_auth_maintenance', 'no')
		));
	}

	public function save_settings(WP_REST_Request $request) {
		$params = $request->get_json_params();
		if (isset($params['site_name'])) update_option('blogname', sanitize_text_field($params['site_name']));
		if (isset($params['site_desc'])) update_option('blogdescription', sanitize_text_field($params['site_desc']));
		if (isset($params['admin_email'])) update_option('admin_email', sanitize_email($params['admin_email']));
		if (isset($params['enable_registration'])) update_option('healthedia_enable_registration', sanitize_text_field($params['enable_registration']));
		if (isset($params['auth_maintenance_mode'])) update_option('healthedia_auth_maintenance', sanitize_text_field($params['auth_maintenance_mode']));
		return rest_ensure_response(array('success' => true, 'message' => 'Settings saved.'));
	}
}
