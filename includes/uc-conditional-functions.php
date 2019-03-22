<?php
/**
 * Conditional Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if an endpoint is showing.
 */
function is_uc_endpoint_url( $endpoint = false ) {
	global $wp;

	$uc_endpoints = uc()->query->get_query_vars();

	if ( false !== $endpoint ) {
		if ( ! isset( $uc_endpoints[ $endpoint ] ) ) {
			return false;
		} else {
			$endpoint_var = $uc_endpoints[ $endpoint ];
		}

		return isset( $wp->query_vars[ $endpoint_var ] );
	} else {
		foreach ( $uc_endpoints as $key => $value ) {
			if ( isset( $wp->query_vars[ $key ] ) ) {
				return true;
			}
		}

		return false;
	}
}