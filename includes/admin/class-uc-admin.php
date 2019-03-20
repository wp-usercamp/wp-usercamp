<?php
/**
 * Admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Admin class.
 */
class UC_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once dirname( __FILE__ ) . '/uc-admin-functions.php';
		include_once dirname( __FILE__ ) . '/uc-meta-box-functions.php';
		include_once dirname( __FILE__ ) . '/class-uc-admin-post-types.php';
		include_once dirname( __FILE__ ) . '/class-uc-admin-menus.php';
		include_once dirname( __FILE__ ) . '/class-uc-admin-notices.php';
		include_once dirname( __FILE__ ) . '/class-uc-admin-assets.php';
	}

}

return new UC_Admin();