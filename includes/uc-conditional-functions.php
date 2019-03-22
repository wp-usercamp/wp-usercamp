<?php
/**
 * Conditional Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns true when viewing an account page.
 */
function is_uc_account_page() {
	$page_id = uc_get_page_id( 'myaccount' );

	return ( $page_id && is_page( $page_id ) ) || uc_post_content_has_shortcode( 'usercamp_account' );
}

/**
 * Checks whether the content passed contains a specific short code.
 */
function uc_post_content_has_shortcode( $tag = '' ) {
	global $post;

	return is_singular() && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $tag );
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