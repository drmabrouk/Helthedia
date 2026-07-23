<?php
class Healthedia {
	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		$this->plugin_name = 'healthedia';
		$this->version = HEALTHEDIA_VERSION;
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->init_modules();
	}

	private function load_dependencies() {
		require_once HEALTHEDIA_PLUGIN_DIR . 'includes/class-healthedia-loader.php';
		require_once HEALTHEDIA_PLUGIN_DIR . 'includes/class-healthedia-i18n.php';
		require_once HEALTHEDIA_PLUGIN_DIR . 'includes/class-healthedia-router.php';
		require_once HEALTHEDIA_PLUGIN_DIR . 'includes/Base/interface-module.php';
		require_once HEALTHEDIA_PLUGIN_DIR . 'includes/Base/class-base-controller.php';

		// Load module controllers
		require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Auth/class-auth-controller.php';
		require_once HEALTHEDIA_PLUGIN_DIR . 'modules/SearchEngine/class-search-controller.php';
		require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Profiles/class-profile-controller.php';
		require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Articles/class-article-controller.php';
		require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Directories/class-directory-controller.php';

		require_once HEALTHEDIA_PLUGIN_DIR . 'dashboard/class-dashboard-controller.php';
		require_once HEALTHEDIA_PLUGIN_DIR . 'public/class-public-controller.php';

		$this->loader = new Healthedia_Loader();
	}

	private function set_locale() {
		$plugin_i18n = new Healthedia_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function define_admin_hooks() {
		$dashboard = new Healthedia_Dashboard_Controller( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $dashboard, 'register_routes' );
	}

	private function define_public_hooks() {
		$public = new Healthedia_Public_Controller( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_scripts' );

		$router = new Healthedia_Router();
		$this->loader->add_action( 'init', $router, 'add_rewrite_rules' );
		$this->loader->add_action( 'template_include', $router, 'load_templates' );
	}

	private function init_modules() {
		$modules = [
			new Healthedia_Auth_Controller(),
			new Healthedia_Search_Controller(),
			new Healthedia_Profile_Controller(),
			new Healthedia_Article_Controller(),
			new Healthedia_Directory_Controller()
		];

		foreach ($modules as $module) {
			$module->init($this->loader);
		}
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}
}
