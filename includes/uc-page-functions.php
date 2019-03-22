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

add_filter( 'the_title', 'uc_page_endpoint_title' );