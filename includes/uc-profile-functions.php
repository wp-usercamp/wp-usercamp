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