<?php
/**
 * Template Hooks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hook before output.
 */
add_action( 'template_redirect', 'uc_template_redirect' );

/**
 * Add the ID to form.
 */
add_action( 'usercamp_register_shortcode_end', 		'usercamp_add_form_id' );
add_action( 'usercamp_login_shortcode_end', 		'usercamp_add_form_id' );
add_action( 'usercamp_lostpassword_shortcode_end', 	'usercamp_add_form_id' );
add_action( 'usercamp_account_shortcode_end', 		'usercamp_add_form_id' );

/**
 * Account.
 */
add_action( 'usercamp_account_navigation', 'usercamp_account_navigation' );
add_action( 'usercamp_account_content', 'usercamp_account_content' );

add_action( 'usercamp_before_account_form', 'usercamp_show_endpoint_title' );

/**
 * Page title.
 */
add_filter( 'the_title', 'uc_page_endpoint_title' );