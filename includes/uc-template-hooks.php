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
add_action( 'usercamp_after_register_form_content', 'usercamp_add_form_inputs' );
add_action( 'usercamp_after_login_form_content', 'usercamp_add_form_inputs' );
add_action( 'usercamp_after_lostpassword_form_content', 'usercamp_add_form_inputs' );
add_action( 'usercamp_after_account_form_content', 'usercamp_add_form_inputs' );

/**
 * Account.
 */
add_action( 'usercamp_account_navigation', 'usercamp_account_navigation' );
add_action( 'usercamp_account_content', 'usercamp_account_content' );
add_action( 'usercamp_before_account_form', 'usercamp_show_endpoint_title' );
add_action( 'uc_get_column_fields', 'uc_add_new_password_fields', 10, 4 );
add_filter( 'uc_get_account_user_pass_field', 'uc_get_account_user_pass_field', 10, 2 );
add_filter( 'uc_get_account_new_password_field', 'uc_get_account_new_password_field', 10, 2 );
add_filter( 'uc_get_account_verify_new_password_field', 'uc_get_account_verify_new_password_field', 10, 2 );

/**
 * Page title.
 */
add_filter( 'the_title', 'uc_page_endpoint_title' );