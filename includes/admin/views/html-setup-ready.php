<?php
/**
 * Admin View: Setup - Ready.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// We've made it! Don't prompt the user to run the wizard again.
//UC_Admin_Notices::remove_notice( 'install' );
?>

<h1><?php esc_html_e( 'Your online community is ready!', 'usercamp' ); ?></h1>

<p><?php esc_html_e( 'What would you like to do next?', 'usercamp' ); ?></p>