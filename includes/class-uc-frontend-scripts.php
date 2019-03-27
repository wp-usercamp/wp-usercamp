<?php
/**
 * Handle frontend scripts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Frontend_Scripts class.
 */
class UC_Frontend_Scripts {

	/**
	 * Contains an array of script handles registered.
	 */
	private static $scripts = array();

	/**
	 * Contains an array of script handles registered.
	 */
	private static $styles = array();

	/**
	 * Contains an array of script handles localized.
	 */
	private static $wp_localize_scripts = array();

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
		add_action( 'wp_print_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
	}

	/**
	 * Get styles for the frontend.
	 */
	public static function get_styles() {
		return apply_filters(
			'usercamp_enqueue_styles',
			array(
				'usercamp-layout'      => array(
					'src'     => self::get_asset_url( 'assets/css/usercamp-layout.css' ),
					'deps'    => '',
					'version' => UC_VERSION,
					'media'   => 'all',
					'has_rtl' => true,
				),
				'usercamp-smallscreen' => array(
					'src'     => self::get_asset_url( 'assets/css/usercamp-smallscreen.css' ),
					'deps'    => 'usercamp-layout',
					'version' => UC_VERSION,
					'media'   => 'only screen and (max-width: ' . apply_filters( 'usercamp_style_smallscreen_breakpoint', '768px' ) . ')',
					'has_rtl' => true,
				),
				'usercamp-general'     => array(
					'src'     => self::get_asset_url( 'assets/css/usercamp.css' ),
					'deps'    => '',
					'version' => UC_VERSION,
					'media'   => 'all',
					'has_rtl' => true,
				),
			)
		);
	}

	/**
	 * Return asset URL.
	 */
	private static function get_asset_url( $path ) {
		return apply_filters( 'usercamp_get_asset_url', plugins_url( $path, UC_PLUGIN_FILE ), $path );
	}

	/**
	 * Register a script for use.
	 */
	private static function register_script( $handle, $path, $deps = array( 'jquery' ), $version = UC_VERSION, $in_footer = true ) {
		self::$scripts[] = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );
	}

	/**
	 * Register and enqueue a script for use.
	 */
	private static function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = UC_VERSION, $in_footer = true ) {
		if ( ! in_array( $handle, self::$scripts, true ) && $path ) {
			self::register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	/**
	 * Register a style for use.
	 */
	private static function register_style( $handle, $path, $deps = array(), $version = UC_VERSION, $media = 'all', $has_rtl = false ) {
		self::$styles[] = $handle;
		wp_register_style( $handle, $path, $deps, $version, $media );

		if ( $has_rtl ) {
			wp_style_add_data( $handle, 'rtl', 'replace' );
		}
	}

	/**
	 * Register and enqueue a styles for use.
	 */
	private static function enqueue_style( $handle, $path = '', $deps = array(), $version = UC_VERSION, $media = 'all', $has_rtl = false ) {
		if ( ! in_array( $handle, self::$styles, true ) && $path ) {
			self::register_style( $handle, $path, $deps, $version, $media, $has_rtl );
		}
		wp_enqueue_style( $handle );
	}

	/**
	 * Register all scripts.
	 */
	private static function register_scripts() {
		$register_scripts = array(
			'jquery-tiptip'					=> array(
				'src'     => self::get_asset_url( 'assets/js/jquery-tiptip/jquery-tiptip.js' ),
				'deps'    => array( 'jquery' ),
				'version' => UC_VERSION,
			),
			'jquery-toggles'				=> array(
				'src'     => self::get_asset_url( 'assets/js/jquery-toggles/jquery-toggles.js' ),
				'deps'    => array( 'jquery' ),
				'version' => UC_VERSION,
			),
			'usercamp'						=> array(
				'src'     => self::get_asset_url( 'assets/js/frontend/usercamp.js' ),
				'deps'    => array( 'jquery', 'jquery-tiptip', 'jquery-toggles' ),
				'version' => UC_VERSION,
			),
		);
		foreach ( $register_scripts as $name => $props ) {
			self::register_script( $name, $props['src'], $props['deps'], $props['version'] );
		}
	}

	/**
	 * Register all styles.
	 */
	private static function register_styles() {
		$register_styles = array(

		);
		foreach ( $register_styles as $name => $props ) {
			self::register_style( $name, $props['src'], $props['deps'], $props['version'], 'all', $props['has_rtl'] );
		}
	}

	/**
	 * Register/queue frontend scripts.
	 */
	public static function load_scripts() {
		global $post;

		if ( ! did_action( 'before_usercamp_init' ) ) {
			return;
		}

		self::register_scripts();
		self::register_styles();

		// Global frontend scripts.
		self::enqueue_script( 'usercamp' );

		// CSS Styles.
		$enqueue_styles = self::get_styles();
		if ( $enqueue_styles ) {
			foreach ( $enqueue_styles as $handle => $args ) {
				if ( ! isset( $args['has_rtl'] ) ) {
					$args['has_rtl'] = false;
				}

				self::enqueue_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'], $args['has_rtl'] );
			}
		}
	}

	/**
	 * Localize a script once.
	 */
	private static function localize_script( $handle ) {
		if ( ! in_array( $handle, self::$wp_localize_scripts, true ) && wp_script_is( $handle ) ) {
			$data = self::get_script_data( $handle );

			if ( ! $data ) {
				return;
			}

			$name                        = str_replace( '-', '_', $handle ) . '_params';
			self::$wp_localize_scripts[] = $handle;
			wp_localize_script( $handle, $name, apply_filters( $name, $data ) );
		}
	}

	/**
	 * Return data for script handles.
	 */
	private static function get_script_data( $handle ) {
		global $wp;

		switch ( $handle ) {
			case 'usercamp':
				$params = array(
					'ajaxurl'    		=> uc()->ajax_url(),
					'yes'				=> __( 'yes', 'usercamp' ),
					'no'				=> __( 'no', 'usercamp' ),
					'svg'				=> uc()->plugin_url() . '/assets/images/feather-sprite.svg#',
				);
				break;
			default:
				$params = false;
		}

		return apply_filters( 'usercamp_get_script_data', $params, $handle );
	}

	/**
	 * Localize scripts only when enqueued.
	 */
	public static function localize_printed_scripts() {
		foreach ( self::$scripts as $handle ) {
			self::localize_script( $handle );
		}
	}

}

UC_Frontend_Scripts::init();