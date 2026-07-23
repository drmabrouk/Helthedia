<?php
class Healthedia_Router {
	public function add_rewrite_rules() {
		add_rewrite_rule('^healthedia-admin/?', 'index.php?healthedia_dashboard=1', 'top');
		add_rewrite_rule('^healthedia-admin/(.*)?', 'index.php?healthedia_dashboard=1', 'top');
		add_rewrite_rule('^dashboard/?', 'index.php?healthedia_dashboard=1', 'top');

		add_rewrite_rule('^login/?', 'index.php?healthedia_auth_page=1', 'top');
		add_rewrite_rule('^register/?', 'index.php?healthedia_auth_page=1', 'top');
		add_rewrite_rule('^auth/?', 'index.php?healthedia_auth_page=1', 'top');

		add_rewrite_rule('^directory/?', 'index.php?healthedia_page=directory', 'top');
		add_rewrite_rule('^academies/?', 'index.php?healthedia_page=academies', 'top');

		add_rewrite_rule('^journal/?', 'index.php?healthedia_page=journal_archive', 'top');

		add_rewrite_rule('^archive-search/?', 'index.php?healthedia_page=archive_search', 'top');

		add_rewrite_rule('^profile/([^/]+)/?', 'index.php?healthedia_profile=$matches[1]', 'top');
		add_rewrite_rule('^u/([^/]+)/?', 'index.php?healthedia_username=$matches[1]', 'top');

		add_rewrite_rule('^account-settings/?', 'index.php?healthedia_page=member_settings', 'top');
		add_rewrite_rule('^saved-research/?', 'index.php?healthedia_page=member_saved', 'top');
		add_rewrite_rule('^my-requests/?', 'index.php?healthedia_page=member_requests', 'top');

		add_rewrite_rule('^submit-manuscript/?', 'index.php?healthedia_page=submit_manuscript', 'top');

		add_rewrite_tag('%healthedia_dashboard%', '1');
		add_rewrite_tag('%healthedia_auth_page%', '1');
		add_rewrite_tag('%healthedia_page%', '([^&]+)');
		add_rewrite_tag('%healthedia_profile%', '([^&]+)');
		add_rewrite_tag('%healthedia_username%', '([^&]+)');
	}

	public function redirect_wp_login() {
		if ( !isset($_REQUEST['action']) || $_REQUEST['action'] === 'login' ) {
			wp_redirect( home_url( '/login' ) );
			die();
		}
	}

	public function redirect_after_logout() {
		wp_safe_redirect( home_url() );
		exit();
	}

	public function load_templates($template) {
		$dashboard = get_query_var('healthedia_dashboard');
		if ($dashboard) {
			if (!current_user_can('manage_options')) {
				wp_redirect(home_url('/login'));
				die();
			}
			return HEALTHEDIA_PLUGIN_DIR . 'dashboard/views/app.php';
		}

		$auth_page = get_query_var('healthedia_auth_page');
		if ($auth_page) {
			if (is_user_logged_in()) {
				wp_redirect(home_url());
				die();
			}
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-auth.php';
		}

		$page = get_query_var('healthedia_page');
		if ($page == 'archive_search') return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-archive-search.php';
		if ($page == 'directory') return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-directory.php';
		if ($page == 'academies') return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-academies.php';
		if ($page == 'journal_archive') return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-journal-archive.php';

		if (in_array($page, ['member_settings', 'member_saved', 'member_requests'])) {
			if (!is_user_logged_in()) {
				wp_redirect(home_url('/login'));
				die();
			}
			return HEALTHEDIA_PLUGIN_DIR . "public/views/page-{$page}.php";
		}

		if ($page == 'submit_manuscript') {
			if (!is_user_logged_in()) {
				wp_redirect(home_url('/login'));
				die();
			}
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-submit-manuscript.php';
		}

		$profile = get_query_var('healthedia_profile');
		if ($profile) {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/single-profile.php';
		}

		global $post, $wp_query;

		// Custom Root-level Username Routing
		$request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
		// Remove WP subdirectory if installed in one
		$home_path = trim(parse_url(home_url(), PHP_URL_PATH), '/');
		if ($home_path && strpos($request_path, $home_path) === 0) {
			$request_path = trim(substr($request_path, strlen($home_path)), '/');
		}

		// If it's a simple one-level path and not a known page, check if it's a username
		if (!empty($request_path) && strpos($request_path, '/') === false) {
			$user = get_user_by('slug', $request_path);
			if (!$user) {
				// Try checking custom meta if we use it, or user_login
				$user = get_user_by('login', $request_path);
			}
			if (!$user) {
				// Also check custom meta _healthedia_username
				$users = get_users(array(
					'meta_key' => '_healthedia_username',
					'meta_value' => $request_path,
					'number' => 1
				));
				if (!empty($users)) $user = $users[0];
			}

			if ($user) {
				// Prevent 404
				$wp_query->is_404 = false;
				status_header(200);
				set_query_var('healthedia_username', $request_path);
				set_query_var('healthedia_profile_user', $user);
				return HEALTHEDIA_PLUGIN_DIR . 'public/views/single-profile.php';
			}
		}

		$username = get_query_var('healthedia_username');
		if ($username) {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/single-profile.php';
		}
		if (isset($post->post_name) && in_array($post->post_name, ['privacy-policy', 'terms-of-service', 'publication-policies', 'certificate-verification', 'support'])) {
			if ($post->post_name === 'certificate-verification') return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-certificate-verification.php';
			if ($post->post_name === 'support') return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-support.php';
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-legal.php';
		}

		if (is_front_page() || is_home() || (isset($post->post_name) && $post->post_name === 'gateway')) {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-gateway.php';
		}

		return $template;
	}
}
