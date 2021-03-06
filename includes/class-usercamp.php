<?php
/**
 * Usercamp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class.
 */
final class UserCamp {

	/**
	 * Define Globals.
	 */
	public $version = '1.0.0';
	protected static $_instance = null;

	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Auto-load in-accessible properties on demand.
	 */
	public function __get( $key ) {
		if ( in_array( $key, array( 'mailer' ), true ) ) {
			return $this->$key();
		}
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'usercamp_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		register_activation_hook( UC_PLUGIN_FILE, array( 'UC_Install', 'install' ) );
		add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'init', array( 'UC_Shortcodes', 'init' ) );
	}

	/**
	 * Define Constants.
	 */
	private function define_constants() {
		$this->define( 'UC_ABSPATH', dirname( UC_PLUGIN_FILE ) . '/' );
		$this->define( 'UC_PLUGIN_BASENAME', plugin_basename( UC_PLUGIN_FILE ) );
		$this->define( 'UC_VERSION', $this->version );
	}

	/**
	 * Define constant if not already set.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		/**
		 * Class autoloader.
		 */
		include_once UC_ABSPATH . 'includes/class-uc-autoloader.php';

		/**
		 * Core classes.
		 */
		include_once UC_ABSPATH . 'includes/uc-core-functions.php';
		include_once UC_ABSPATH . 'includes/class-uc-ajax.php';
		include_once UC_ABSPATH . 'includes/class-uc-post-types.php';
		include_once UC_ABSPATH . 'includes/class-uc-install.php';
		include_once UC_ABSPATH . 'includes/class-uc-shortcodes.php';
		include_once UC_ABSPATH . 'includes/class-uc-query.php';

		/**
		 * Data stores
		 */
		include_once UC_ABSPATH . 'includes/class-uc-data-store.php';
		include_once UC_ABSPATH . 'includes/data-stores/class-uc-data-store-wp.php';
		include_once UC_ABSPATH . 'includes/data-stores/class-uc-form-data-store.php';
		include_once UC_ABSPATH . 'includes/data-stores/class-uc-field-data-store.php';
		include_once UC_ABSPATH . 'includes/data-stores/class-uc-role-data-store.php';
		include_once UC_ABSPATH . 'includes/data-stores/class-uc-memberlist-data-store.php';

		if ( $this->is_request( 'admin' ) ) {
			include_once UC_ABSPATH . 'includes/admin/class-uc-admin.php';
		}

		if ( $this->is_request( 'frontend' ) ) {
			$this->frontend_includes();
		}

		$this->query = new UC_Query();
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		include_once UC_ABSPATH . 'includes/uc-notice-functions.php';
		include_once UC_ABSPATH . 'includes/uc-template-hooks.php';
		include_once UC_ABSPATH . 'includes/class-uc-frontend-scripts.php';
		include_once UC_ABSPATH . 'includes/class-uc-form-handler.php';
	}

	/**
	 * Function used to Init Template Functions - This makes them pluggable by plugins and themes.
	 */
	public function include_template_functions() {
		include_once UC_ABSPATH . 'includes/uc-template-functions.php';
	}

	/**
	 * Init when WordPress Initialises.
	 */
	public function init() {
		// Before init action.
		do_action( 'before_usercamp_init' );

		// Set up localisation.
		$this->load_plugin_textdomain();

		// Init action.
		do_action( 'usercamp_init' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/usercamp/usercamp-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/usercamp-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'usercamp' );

		unload_textdomain( 'usercamp' );
		load_textdomain( 'usercamp', WP_LANG_DIR . '/usercamp/usercamp-' . $locale . '.mo' );
		load_plugin_textdomain( 'usercamp', false, plugin_basename( dirname( UC_PLUGIN_FILE ) ) . '/i18n/languages' );
	}

	/**
	 * Get the plugin url.
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', UC_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin path.
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( UC_PLUGIN_FILE ) );
	}

	/**
	 * Get the template path.
	 */
	public function template_path() {
		return apply_filters( 'usercamp_template_path', 'usercamp/' );
	}

	/**
	 * Get Ajax URL.
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Email class.
	 */
	public function mailer() {
		return UC_Emails::instance();
	}

}