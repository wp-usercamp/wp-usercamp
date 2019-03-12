<?php
/**
 * Create menus in WP admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Admin_Menus Class.
 */
class UC_Admin_Menus {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Add menus.
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
		add_action( 'admin_menu', array( $this, 'settings_menu' ), 50 );

		if ( apply_filters( 'usercamp_show_addons_page', true ) ) {
			add_action( 'admin_menu', array( $this, 'addons_menu' ), 70 );
		}

		add_action( 'admin_head', array( $this, 'menu_highlight' ) );
		add_action( 'admin_head', array( $this, 'menu_order_fix' ) );
		add_filter( 'menu_order', array( $this, 'menu_order' ) );
		add_filter( 'custom_menu_order', array( $this, 'custom_menu_order' ) );
	}

	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		global $menu;

		if ( current_user_can( 'manage_usercamp' ) ) {
			$menu[] = array( '', 'read', 'separator-usercamp', '', 'wp-menu-separator usercamp' );
		}

		add_menu_page( __( 'Usercamp', 'usercamp' ), __( 'Usercamp', 'usercamp' ), 'manage_usercamp', 'usercamp', null, null, '58.5471' );
	}

	/**
	 * Add menu item.
	 */
	public function settings_menu() {
		$settings_page = add_submenu_page( 'usercamp', __( 'Usercamp settings', 'usercamp' ), __( 'Settings', 'usercamp' ), 'uc_settings', 'uc-settings', array( $this, 'settings_page' ) );
		add_action( 'load-' . $settings_page, array( $this, 'settings_page_init' ) );
	}

	/**
	 * Addons menu item.
	 */
	public function addons_menu() {
		$menu_title = __( 'Add-ons', 'usercamp' );
		add_submenu_page( 'usercamp', __( 'Usercamp add-ons', 'usercamp' ), $menu_title, 'uc_addons', 'uc-addons', array( $this, 'addons_page' ) );
	}

	/**
	 * Highlights the correct top level admin menu item for post type add screens.
	 */
	public function menu_highlight() {
		global $parent_file, $submenu_file, $post_type;

		switch ( $post_type ) {

		}
	}

	/**
	 * Removes the parent menu item.
	 */
	public function menu_order_fix() {
		global $submenu;

		if ( isset( $submenu['usercamp'] ) ) {
			// Remove 'Usercamp' sub menu item.
			unset( $submenu['usercamp'][0] );
		}
	}

	/**
	 * Reorder the menu items in admin.
	 */
	public function menu_order( $menu_order ) {
		// Initialize our custom order array.
		$usercamp_menu_order = array();

		// Get the index of our custom separator.
		$usercamp_separator = array_search( 'separator-usercamp', $menu_order, true );

		// Loop through menu order and do some rearranging.
		foreach ( $menu_order as $index => $item ) {

			if ( 'usercamp' === $item ) {
				$usercamp_menu_order[] = 'separator-usercamp';
				$usercamp_menu_order[] = $item;
				$usercamp_menu_order[] = 'edit.php?post_type=product';
				unset( $menu_order[ $usercamp_separator ] );
			} elseif ( ! in_array( $item, array( 'separator-usercamp' ), true ) ) {
				$usercamp_menu_order[] = $item;
			}
		}

		// Return order.
		return $usercamp_menu_order;
	}

	/**
	 * Custom menu order.
	 */
	public function custom_menu_order( $enabled ) {
		return $enabled || current_user_can( 'manage_usercamp' );
	}

	/**
	 * Init the settings page.
	 */
	public function settings_page() {

	}

	/**
	 * Init the addons page.
	 */
	public function addons_page() {

	}

	/**
	 * Loads gateways and shipping methods into memory for use within settings.
	 */
	public function settings_page_init() {
		global $current_tab, $current_section;
	}

}

return new UC_Admin_Menus();