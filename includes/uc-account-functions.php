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
		'edit-account'				=> get_option( 'usercamp_account_edit_account_endpoint', 'edit-account' ),
		'edit-password'       		=> get_option( 'usercamp_account_edit_password_endpoint', 'edit-password' ),
		'privacy'              		=> get_option( 'usercamp_account_privacy_endpoint', 'privacy' ),
		'email-notifications'		=> get_option( 'usercamp_account_email_notifications_endpoint', 'email-notifications' ),
	);

	$endpoints = apply_filters( 'usercamp_get_account_endpoints', ( array ) $endpoints );

	// Make sure logout is added after every other filter.
	if ( ! array_key_exists( 'logout', $endpoints ) ) {
		$endpoints[ 'logout' ] = get_option( 'usercamp_account_logout_endpoint', 'logout' );
	}

	return $endpoints;
}

/**
 * Get account default endpoint.
 */
function uc_get_account_default_endpoint() {
	return apply_filters( 'uc_get_account_default_endpoint', 'edit-account' );
}

/**
 * Get account endpoint URL.
 */
function uc_get_account_endpoint_url( $endpoint ) {
	if ( 'logout' === $endpoint ) {
		return uc_logout_url();
	}

	return uc_get_endpoint_url( $endpoint, '', uc_get_page_permalink( 'account' ) );
}

/**
 * Get account endpoint form.
 */
function uc_get_account_endpoint_form() {
	$endpoint = uc()->query->get_current_endpoint();
	if ( $endpoint ) {
		return get_option( 'usercamp_account_' . uc_sanitize_title( $endpoint ) . '_form' );
	}
	return get_option( 'usercamp_account_form', '' );
}

/**
 * Get account menu items.
 */
function uc_get_account_menu_items() {

	$endpoints = uc_get_account_endpoints();
	$default = uc_get_account_default_endpoint();

	// Remove missing endpoints.
	foreach ( $endpoints as $endpoint_id => $endpoint ) {
		if ( empty( $endpoint ) ) {
			unset( $items[ $endpoint_id ] );
		}
		$items[ $endpoint_id ] = uc()->query->get_endpoint_title( $endpoint );
	}

	// Make sure that default endpoint comes on top.
	$default_endpoint = $items[ $default ];
	unset( $items[ $default ] );
	$items = array_merge( array( $default => $default_endpoint ) , $items );

	// Ensure logout comes at the very bottom of list.
	$logout = $items[ 'logout' ];
	unset( $items[ 'logout' ] );
	$items[ 'logout' ] = $logout;

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
	if ( uc_get_account_default_endpoint() === $endpoint && ( isset( $wp->query_vars['page'] ) || empty( $wp->query_vars ) ) ) {
		$current = true;
	}

	if ( $current ) {
		$classes[] = 'is-active';
	}

	$classes = apply_filters( 'usercamp_account_menu_item_classes', $classes, $endpoint );

	return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
}