<?php
/**
 * Usercamp Uninstall
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
define( 'UC_ABSPATH', dirname( __FILE__ ) . '/' );

global $wpdb, $wp_version;

/*
 * Only remove ALL product and page data if UC_REMOVE_ALL_DATA constant is set to true in user's
 * wp-config.php. This is to prevent data loss when deleting the plugin from the backend
 * and to ensure only the site owner can perform this action.
 */
if ( defined( 'UC_REMOVE_ALL_DATA' ) && true === UC_REMOVE_ALL_DATA ) {
	include_once dirname( __FILE__ ) . '/includes/uc-core-functions.php';
	include_once dirname( __FILE__ ) . '/includes/class-uc-install.php';

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
	UC_Install::remove_roles();

	// Remove directories and files.
	UC_Install::remove_files();

	// Clear any cached data that has been removed.
	wp_cache_flush();
}