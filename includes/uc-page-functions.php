<?php
/**
 * Page Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Replace a page title with the endpoint title.
 */
function uc_page_endpoint_title( $title ) {
	global $wp_query;

	if ( ! is_null( $wp_query ) && ! is_admin() && is_main_query() && in_the_loop() && is_page() && is_uc_endpoint_url() ) {
		$endpoint       = uc()->query->get_current_endpoint();
		$endpoint_title = uc()->query->get_endpoint_title( $endpoint );
		$title          = $endpoint_title ? $endpoint_title : $title;

		remove_filter( 'the_title', 'uc_page_endpoint_title' );
	}

	return $title;
}

/**
 * Retrieve page permalink.
 */
function uc_get_page_permalink( $page, $fallback = null ) {
	$page_id   = uc_get_page_id( $page );
	$permalink = 0 < $page_id ? get_permalink( $page_id ) : '';

	if ( ! $permalink ) {
		$permalink = is_null( $fallback ) ? get_home_url() : $fallback;
	}

	return apply_filters( 'usercamp_get_' . $page . '_page_permalink', $permalink );
}

/**
 * Retrieve page ids
 */
function uc_get_page_id( $page ) {

	$page = apply_filters( 'usercamp_get_' . $page . '_page_id', get_option( 'usercamp_' . $page . '_page_id' ) );

	return $page ? absint( $page ) : -1;
}

/**
 * Get endpoint URL.
 */
function uc_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {
	if ( ! $permalink ) {
		$permalink = get_permalink();
	}

	// Map endpoint to options.
	$query_vars = uc()->query->get_query_vars();
	$endpoint   = ! empty( $query_vars[ $endpoint ] ) ? $query_vars[ $endpoint ] : $endpoint;

	if ( get_option( 'permalink_structure' ) ) {
		if ( strstr( $permalink, '?' ) ) {
			$query_string = '?' . wp_parse_url( $permalink, PHP_URL_QUERY );
			$permalink    = current( explode( '?', $permalink ) );
		} else {
			$query_string = '';
		}
		$url = trailingslashit( $permalink ) . untrailingslashit( $endpoint );

		if ( $value ) {
			$url .= trailingslashit( $value );
		}

		$url .= $query_string;
	} else {
		$url = add_query_arg( $endpoint, $value, $permalink );
	}

	return apply_filters( 'usercamp_get_endpoint_url', $url, $endpoint, $value, $permalink );
}