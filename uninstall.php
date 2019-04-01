<?php
/**
 * Usercamp Uninstall
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
define( 'UC_ABSPATH', dirname( __FILE__ ) . '/' );

// Load the install class into memory to uninstall.
include_once dirname( __FILE__ ) . '/includes/class-uc-install.php';
UC_Install::uninstall();