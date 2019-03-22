<?php
/**
 * Account Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get account endpoint URL.
 */
function uc_get_account_endpoint_url( $endpoint ) {
	if ( 'dashboard' === $endpoint ) {
		return uc_get_page_permalink( 'myaccount' );
	}

	if ( 'logout' === $endpoint ) {
		return uc_logout_url();
	}

	return uc_get_endpoint_url( $endpoint, '', uc_get_page_permalink( 'myaccount' ) );
}