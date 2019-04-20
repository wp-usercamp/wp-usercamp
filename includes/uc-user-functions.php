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