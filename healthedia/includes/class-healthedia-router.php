<?php
class Healthedia_Router {
	public function add_rewrite_rules() {
		// Dashboard routing
		add_rewrite_rule('^healthedia-admin/?', 'index.php?healthedia_dashboard=1', 'top');
		add_rewrite_rule('^healthedia-admin/(.*)?', 'index.php?healthedia_dashboard=1', 'top');

		// Auth endpoints
		add_rewrite_rule('^auth/login/?', 'index.php?healthedia_auth=login', 'top');
		add_rewrite_rule('^auth/verify/?', 'index.php?healthedia_auth=verify', 'top');

		// Directories & Articles
		add_rewrite_rule('^directories/?', 'index.php?healthedia_page=directory', 'top');
		add_rewrite_rule('^journal/?', 'index.php?healthedia_page=journal', 'top');

		// Profile
		add_rewrite_rule('^profile/([^/]+)/?', 'index.php?healthedia_profile=$matches[1]', 'top');

		// Tags for query vars
		add_rewrite_tag('%healthedia_dashboard%', '1');
		add_rewrite_tag('%healthedia_auth%', '([^&]+)');
		add_rewrite_tag('%healthedia_page%', '([^&]+)');
		add_rewrite_tag('%healthedia_profile%', '([^&]+)');
	}

	public function load_templates($template) {
		$dashboard = get_query_var('healthedia_dashboard');
		if ($dashboard) {
			return HEALTHEDIA_PLUGIN_DIR . 'dashboard/views/app.php';
		}

		$page = get_query_var('healthedia_page');
		if ($page == 'directory') {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-directory.php';
		}
		if ($page == 'journal') {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/single-article.php'; // Or gateway/index depending on setup
		}

		$profile = get_query_var('healthedia_profile');
		if ($profile) {
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/single-profile.php';
		}

		// If on homepage, check if we want to replace with gateway
		if (is_front_page() || is_home()) {
			// For this plugin, we hijack the front page for the Central Gateway
			return HEALTHEDIA_PLUGIN_DIR . 'public/views/page-gateway.php';
		}

		return $template;
	}
}
