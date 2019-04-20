<?php
/**
 * Profile Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get profile endpoints.
 */
function uc_get_profile_endpoints() {
	$endpoints = array(
		'uc_user'	=> '',
	);

	$endpoints = apply_filters( 'uc_get_profile_endpoints', ( array ) $endpoints );

	return $endpoints;
}

/**
 * Get active profile ID.
 */
function uc_get_active_profile_id() {
	return apply_filters( 'uc_get_active_profile_id', username_exists( esc_attr( get_query_var( 'uc_user' ) ) ) );
}

/**
 * Get a user profile URL.
 */
function usercamp_get_profile_url( $username = '', $ajax = false ) {
	global $the_user;
	if ( empty( $username ) ) {
		$username = $the_user->get( 'user_login', 'edit' );
	}
	$permalink = uc_get_page_permalink( 'profile' );

	if ( $ajax ) {
		$username = '<span class="uc-ajax">' . $username . '</span>';
	}

	return apply_filters( 'usercamp_get_profile_url', uc_get_endpoint_url( $username, '', $permalink ), $username, $the_user );
}