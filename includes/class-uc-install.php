<?php
/**
 * Installation functions and actions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Install class.
 */
class UC_Install {

	/**
	 * Hook in tabs.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
		add_filter( 'plugin_action_links_' . UC_PLUGIN_BASENAME, array( __CLASS__, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Check version and run the updater is required.
	 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && version_compare( get_option( 'usercamp_version' ), uc()->version, '<' ) ) {
			self::install();
			do_action( 'usercamp_updated' );
		}
	}

	/**
	 * Install.
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'uc_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'uc_installing', 'yes', MINUTE_IN_SECONDS * 10 );
		uc_maybe_define_constant( 'UC_INSTALLING', true );

		self::remove_admin_notices();
		self::create_roles();
		self::setup_environment();
		self::create_files();
		self::maybe_enable_setup_wizard();
		self::update_uc_version();

		delete_transient( 'uc_installing' );

		do_action( 'usercamp_flush_rewrite_rules' );
		do_action( 'usercamp_installed' );
	}

	/**
	 * Reset any notices added to admin.
	 */
	private static function remove_admin_notices() {
		include_once dirname( __FILE__ ) . '/admin/class-uc-admin-notices.php';
		UC_Admin_Notices::remove_all_notices();
	}

	/**
	 * Setup environment - post types, taxonomies, endpoints.
	 */
	private static function setup_environment() {
		UC_Post_types::register_post_types();
		uc()->query->init_query_vars();
		uc()->query->add_endpoints();
	}

	/**
	 * See if we need the wizard or not.
	 */
	private static function maybe_enable_setup_wizard() {
		if ( apply_filters( 'usercamp_enable_setup_wizard', self::is_new_install() ) ) {
			UC_Admin_Notices::add_notice( 'install' );
			set_transient( '_uc_activation_redirect', 1, 30 );
		}
	}

	/**
	 * Is this a brand new install?
	 */
	private static function is_new_install() {
		return is_null( get_option( 'usercamp_version', null ) );
	}

	/**
	 * Update version to current.
	 */
	private static function update_uc_version() {
		delete_option( 'usercamp_version' );
		add_option( 'usercamp_version', uc()->version );
	}

	/**
	 * Create roles and capabilities.
	 */
	public static function create_roles() {
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		// Create plugin-specific roles
		_x( 'Member', 'User role', 'usercamp' );
		_x( 'Community manager', 'User role', 'usercamp' );

		usercamp_add_role(
			'member',
			'Member',
			$wp_roles->roles['subscriber']['capabilities']
		);

		usercamp_add_role(
			'community_manager',
			'Community manager',
			$wp_roles->roles['administrator']['capabilities']
		);

		update_option( 'usercamp_roles', array(
			'member',
			'community_manager'
		) );

		// Add default capabilities to all roles.
		foreach( $wp_roles->roles as $role => $data ) {
			if ( in_array( $role, array( 'administrator', 'community_manager' ) ) ) {
				foreach( uc_get_admin_caps() as $cap => $bool ) {
					$wp_roles->add_cap( $role, $cap );
				}
			}
			foreach( uc_get_default_caps() as $cap => $bool ) {
				$wp_roles->add_cap( $role, $cap );
			}
		}

	}

	/**
	 * Create files/directories.
	 */
	private static function create_files() {

		// Install files and folders for uploading files and prevent hotlinking.
		$upload_dir      = wp_upload_dir();

		$files = array(
			array(
				'base'    => $upload_dir['basedir'] . '/usercamp_uploads',
				'file'    => 'index.html',
				'content' => '',
			),
			array(
				'base'    => $upload_dir['basedir'] . '/usercamp_uploads',
				'file'    => '.htaccess',
				'content' => 'deny from all',
			)
		);

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				$file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' );
				if ( $file_handle ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}
		}

	}

	/**
	 * Show action links on the plugin screen.
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=uc-settings' ) . '" aria-label="' . esc_attr__( 'View Usercamp settings', 'usercamp' ) . '">' . esc_html__( 'Settings', 'usercamp' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * Show row meta on the plugin screen.
	 */
	public static function plugin_row_meta( $links, $file ) {
		if ( UC_PLUGIN_BASENAME === $file ) {
			$row_meta = array(
				'docs'    => '<a href="' . esc_url( apply_filters( 'usercamp_docs_url', 'https://docs.usercamp.io' ) ) . '" aria-label="' . esc_attr__( 'View Usercamp documentation', 'usercamp' ) . '">' . esc_html__( 'Docs', 'usercamp' ) . '</a>',
				'apidocs' => '<a href="' . esc_url( apply_filters( 'usercamp_apidocs_url', 'https://docs.usercamp.io/api' ) ) . '" aria-label="' . esc_attr__( 'View Usercamp API docs', 'usercamp' ) . '">' . esc_html__( 'API docs', 'usercamp' ) . '</a>',
				'support' => '<a href="' . esc_url( apply_filters( 'usercamp_support_url', 'https://usercamp.io/support/tickets' ) ) . '" aria-label="' . esc_attr__( 'Visit premium customer support', 'usercamp' ) . '">' . esc_html__( 'Premium support', 'usercamp' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

	/**
	 * Uninstall.
	 */
	public static function uninstall() {
		global $wpdb;

		if ( defined( 'UC_REMOVE_ALL_DATA' ) && true === UC_REMOVE_ALL_DATA ) {

			// Delete options.
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'usercamp\_%';" );
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'widget\_usercamp\_%';" );
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'uc\_%';" );
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%\_uc_\%';" );

			// Delete usermeta.
			$wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'usercamp\_%';" );
			$wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'uc\_%';" );
			$wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE '%\_uc_\%';" );

			// Delete posts.
			$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( 'uc_form', 'uc_field', 'uc_role', 'uc_memberlist' );" );
			$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_content LIKE '[usercamp_form id=%';" );
			$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_content LIKE '[usercamp_account]';" );
			$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_content LIKE '[usercamp_profile]';" );
			$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

			// Roles + caps.
			self::remove_roles();

			// Remove directories and files.
			self::remove_files();

			// Clear any cached data that has been removed.
			wp_cache_flush();
		}
	}

	/**
	 * Remove roles and capabilities.
	 */
	public static function remove_roles() {
		global $wpdb, $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		// Let's remove all plugin roles.
		$roles = get_option( 'usercamp_roles' );
		if ( $roles ) {
			foreach( $roles as $role ) {
				foreach( array_merge( uc_get_default_caps(), uc_get_admin_caps(), uc_get_wp_admin_caps() ) as $cap => $bool ) {
					$wp_roles->remove_cap( $role, $cap );
				}
				$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'uc_role' AND post_name = '{$role}'" );
				remove_role( $role );
			}
			delete_option( 'usercamp_roles' );
		}

		// Let's remove plugin capabilities from other roles.
		foreach( $wp_roles->roles as $role => $data ) {
			foreach( array_merge( uc_get_default_caps(), uc_get_admin_caps() ) as $cap => $bool ) {
				$wp_roles->remove_cap( $role, $cap );
			}
			$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'uc_role' AND post_name = '{$role}'" );
		}
	}

	/**
	 * Remove files and folders.
	 */
	public static function remove_files() {
		global $wp_filesystem;

		// Initialize the WP filesystem.
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		$upload_dir     = wp_upload_dir();
		$remove_dir 	= $upload_dir['basedir'] . '/usercamp_uploads';

		$wp_filesystem->rmdir( $remove_dir, true );

	}

}

UC_Install::init();