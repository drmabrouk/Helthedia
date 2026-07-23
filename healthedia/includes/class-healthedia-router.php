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

		add_rewrite_rule('^profile/([^/]+)/?', 'index.php?healthedia_profile=$matches[1]', 'top');

		add_rewrite_rule('^account-settings/?', 'index.php?healthedia_page=member_settings', 'top');
		add_rewrite_rule('^saved-research/?', 'index.php?healthedia_page=member_saved', 'top');
		add_rewrite_rule('^my-requests/?', 'index.php?healthedia_page=member_requests', 'top');

		add_rewrite_rule('^submit-manuscript/?', 'index.php?healthedia_page=submit_manuscript', 'top');

		add_rewrite_tag('%healthedia_dashboard%', '1');
		add_rewrite_tag('%healthedia_auth_page%', '1');
		add_rewrite_tag('%healthedia_page%', '([^&]+)');
		add_rewrite_tag('%healthedia_profile%', '([^&]+)');
	}

	public function redirect_wp_login() {
		if ( !isset($_REQUEST['action']) || $_REQUEST['action'] === 'login' ) {
			wp_redirect( home_url( '/login' ) );
			die();
		}
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

		global $post;
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
