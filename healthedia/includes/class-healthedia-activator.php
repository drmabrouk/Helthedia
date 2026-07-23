<?php
class Healthedia_Activator {
	public static function activate() {
		require_once HEALTHEDIA_PLUGIN_DIR . 'includes/class-healthedia-db.php';
		Healthedia_DB::create_tables();

		require_once HEALTHEDIA_PLUGIN_DIR . 'includes/class-healthedia-router.php';
		$router = new Healthedia_Router();
		$router->add_rewrite_rules();
		flush_rewrite_rules();
	}
}
