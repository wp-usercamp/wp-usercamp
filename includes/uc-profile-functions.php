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