<?php
/**
 * User Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a user.
 */
function uc_get_user( $user_id = '' ) {
	return new UC_User( absint( $user_id ) );
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