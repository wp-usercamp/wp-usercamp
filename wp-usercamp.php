<?php
/**
 * Plugin Name: Usercamp
 * Plugin URI: https://usercamp.io
 * Description: The world's easiest way to create online communities with WordPress.
 * Version: 1.0.0
 * Author: Usercamp
 * Author URI: https://usercamp.io
 * Text Domain: usercamp
 * Domain Path: /i18n/languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define UC_PLUGIN_FILE.
if ( ! defined( 'UC_PLUGIN_FILE' ) ) {
	define( 'UC_PLUGIN_FILE', __FILE__ );
}

// Include the main class.
if ( ! class_exists( 'UserCamp' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-usercamp.php';
}

/**
 * Main instance.
 */
function uc() {
	return UserCamp::instance();
}

// Global for backwards compatibility.
$GLOBALS['usercamp'] = uc();