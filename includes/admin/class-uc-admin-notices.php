<?php
/**
 * Display notices in admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Admin_Notices Class.
 */
class UC_Admin_Notices {

	/**
	 * Stores notices.
	 */
	private static $notices = array();

	/**
	 * Array of notices - name => callback.
	 */
	private static $core_notices = array(
		'install'                 => 'install_notice',
		'no_secure_connection'    => 'secure_connection_notice',
	);

	/**
	 * Constructor.
	 */
	public static function init() {
		self::$notices = get_option( 'usercamp_admin_notices', array() );
		add_action( 'switch_theme', array( __CLASS__, 'reset_admin_notices' ) );
		add_action( 'usercamp_installed', array( __CLASS__, 'reset_admin_notices' ) );
		add_action( 'wp_loaded', array( __CLASS__, 'hide_notices' ) );
		add_action( 'shutdown', array( __CLASS__, 'store_notices' ) );

		if ( current_user_can( 'manage_usercamp' ) ) {
			add_action( 'admin_print_styles', array( __CLASS__, 'add_notices' ) );
		}
	}

	/**
	 * Store notices to DB
	 */
	public static function store_notices() {
		update_option( 'usercamp_admin_notices', self::get_notices() );
	}

	/**
	 * Get notices
	 */
	public static function get_notices() {
		return self::$notices;
	}

	/**
	 * Remove all notices.
	 */
	public static function remove_all_notices() {
		self::$notices = array();
	}

	/**
	 * Reset notices for themes when switched or a new version of WC is installed.
	 */
	public static function reset_admin_notices() {
		if ( ! self::is_ssl() ) {
			self::add_notice( 'no_secure_connection' );
		}
	}

	/**
	 * Show a notice.
	 */
	public static function add_notice( $name ) {
		self::$notices = array_unique( array_merge( self::get_notices(), array( $name ) ) );
	}

	/**
	 * Remove a notice from being displayed.
	 */
	public static function remove_notice( $name ) {
		self::$notices = array_diff( self::get_notices(), array( $name ) );
		delete_option( 'usercamp_admin_notice_' . $name );
	}

	/**
	 * See if a notice is being shown.
	 */
	public static function has_notice( $name ) {
		return in_array( $name, self::get_notices(), true );
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function hide_notices() {
		if ( isset( $_GET['uc-hide-notice'] ) && isset( $_GET['_uc_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_uc_notice_nonce'] ) ), 'usercamp_hide_notices_nonce' ) ) {
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'usercamp' ) );
			}

			if ( ! current_user_can( 'manage_usercamp' ) ) {
				wp_die( esc_html__( 'You don&#8217;t have permission to do this.', 'usercamp' ) );
			}

			$hide_notice = sanitize_text_field( wp_unslash( $_GET['uc-hide-notice'] ) );

			self::remove_notice( $hide_notice );

			update_user_meta( get_current_user_id(), 'uc_dismissed_' . $hide_notice . '_notice', true );

			do_action( 'usercamp_hide_' . $hide_notice . '_notice' );
		}
	}

	/**
	 * Add notices + styles if needed.
	 */
	public static function add_notices() {
		$notices = self::get_notices();

		if ( empty( $notices ) ) {
			return;
		}

		$screen          = get_current_screen();
		$screen_id       = $screen ? $screen->id : '';
		$show_on_screens = array(
			'dashboard',
			'plugins',
		);

		// Notices should only show on screens, the main dashboard, and on the plugins screen.
		if ( ! in_array( $screen_id, uc_get_screen_ids(), true ) && ! in_array( $screen_id, $show_on_screens, true ) ) {
			return;
		}

		wp_enqueue_style( 'usercamp-activation', plugins_url( '/assets/css/activation.css', UC_PLUGIN_FILE ), array(), UC_VERSION );

		foreach ( $notices as $notice ) {
			if ( ! empty( self::$core_notices[ $notice ] ) && apply_filters( 'usercamp_show_admin_notice', true, $notice ) ) {
				add_action( 'admin_notices', array( __CLASS__, self::$core_notices[ $notice ] ) );
			} else {
				add_action( 'admin_notices', array( __CLASS__, 'output_custom_notices' ) );
			}
		}
	}

	/**
	 * Add a custom notice.
	 */
	public static function add_custom_notice( $name, $notice_html ) {
		self::add_notice( $name );
		update_option( 'usercamp_admin_notice_' . $name, wp_kses_post( $notice_html ) );
	}

	/**
	 * Output any stored custom notices.
	 */
	public static function output_custom_notices() {
		$notices = self::get_notices();

		if ( ! empty( $notices ) ) {
			foreach ( $notices as $notice ) {
				if ( empty( self::$core_notices[ $notice ] ) ) {
					$notice_html = get_option( 'usercamp_admin_notice_' . $notice );

					if ( $notice_html ) {
						include dirname( __FILE__ ) . '/views/html-notice-custom.php';
					}
				}
			}
		}
	}

	/**
	 * If we have just installed, show a message with the install pages button.
	 */
	public static function install_notice() {
		include dirname( __FILE__ ) . '/views/html-notice-install.php';
	}

	/**
	 * Notice about secure connection.
	 */
	public static function secure_connection_notice() {
		if ( self::is_ssl() || get_user_meta( get_current_user_id(), 'uc_dismissed_no_secure_connection_notice', true ) ) {
			return;
		}

		include dirname( __FILE__ ) . '/views/html-notice-secure-connection.php';
	}

	/**
	 * Determine if the store is running SSL.
	 */
	protected static function is_ssl() {
		return ( is_ssl() );
	}

}

UC_Admin_Notices::init();