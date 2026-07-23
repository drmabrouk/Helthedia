<?php
class Healthedia_Router {
	public function add_rewrite_rules() {
		// Dashboard routing
		add_rewrite_rule('^healthedia-admin/?', 'index.php?healthedia_dashboard=1', 'top');
		add_rewrite_rule('^healthedia-admin/(.*)?', 'index.php?healthedia_dashboard=1', 'top');

		// Auth pages
		add_rewrite_rule('^login/?', 'index.php?healthedia_auth_page=1', 'top');
		add_rewrite_rule('^register/?', 'index.php?healthedia_auth_page=1', 'top');

		// Directories & Articles
		add_rewrite_rule('^directories/?', 'index.php?healthedia_page=directory', 'top');
		add_rewrite_rule('^journal/?', 'index.php?healthedia_page=journal', 'top');

		// Profile
		add_rewrite_rule('^profile/([^/]+)/?', 'index.php?healthedia_profile=$matches[1]', 'top');

		// Tags for query vars
		add_rewrite_tag('%healthedia_dashboard%', '1');
		add_rewrite_tag('%healthedia_auth_page%', '1');
		add_rewrite_tag('%healthedia_page%', '([^&]+)');
		add_rewrite_tag('%healthedia_profile%', '([^&]+)');
	}

	public function load_templates($template) {
		$dashboard = get_query_var('healthedia_dashboard');
		if ($dashboard) {
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
		if ($page == 'directory') {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-directory.php';
		}
		if ($page == 'journal') {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/single-article.php';
		}

		$profile = get_query_var('healthedia_profile');
		if ($profile) {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/single-profile.php';
		}

		// If on homepage, check if we want to replace with gateway
		if (is_front_page() || is_home()) {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-gateway.php';
		}

		return $template;
	}
}
