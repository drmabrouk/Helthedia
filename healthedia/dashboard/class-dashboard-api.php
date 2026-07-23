<?php
class Healthedia_Dashboard_API {
	public function register_routes() {
		register_rest_route('healthedia/v1', '/admin/stats', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_stats'),
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
}
