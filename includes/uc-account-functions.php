<?php
/**
 * Account Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get account endpoints.
 */
function uc_get_account_endpoints() {
	$endpoints = array(
		'edit-account'				=> get_option( 'usercamp_myaccount_edit_account_endpoint', 'edit-account' ),
		'edit-password'       		=> get_option( 'usercamp_myaccount_edit_password_endpoint', 'edit-password' ),
		'privacy'              		=> get_option( 'usercamp_myaccount_privacy_endpoint', 'privacy' ),
		'email-notifications'		=> get_option( 'usercamp_myaccount_email_notifications_endpoint', 'email-notifications' ),
		'logout'					=> get_option( 'usercamp_myaccount_logout_endpoint', 'logout' ),
	);

	return apply_filters( 'usercamp_get_account_endpoints', $endpoints );
}

/**
 * Get account endpoint URL.
 */
function uc_get_account_endpoint_url( $endpoint ) {
	if ( 'logout' === $endpoint ) {
		return uc_logout_url();
	}

	return uc_get_endpoint_url( $endpoint, '', uc_get_page_permalink( 'myaccount' ) );
}

/**
 * Get My Account menu items.
 */
function uc_get_account_menu_items() {

	$endpoints = uc_get_account_endpoints();

	// Remove missing endpoints.
	foreach ( $endpoints as $endpoint_id => $endpoint ) {
		if ( empty( $endpoint ) ) {
			unset( $items[ $endpoint_id ] );
		}
		$items[ $endpoint_id ] = uc()->query->get_endpoint_title( $endpoint );
	}

	return apply_filters( 'usercamp_account_menu_items', $items, $endpoints );
}

/**
 * Get account menu item classes.
 */
function uc_get_account_menu_item_classes( $endpoint ) {
	global $wp;

	$classes = array(
		'usercamp-account-navigation-link',
		'usercamp-account-navigation-link--' . $endpoint,
	);

	// Set current item class.
	$current = isset( $wp->query_vars[ $endpoint ] );

	// Fallback to default content item.
	if ( 'edit-account' === $endpoint && ( isset( $wp->query_vars['page'] ) || empty( $wp->query_vars ) ) ) {
		$current = true;
	}

	if ( $current ) {
		$classes[] = 'is-active';
	}

	$classes = apply_filters( 'usercamp_account_menu_item_classes', $classes, $endpoint );

	return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
}