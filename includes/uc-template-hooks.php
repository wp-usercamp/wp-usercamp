<?php
/**
 * Template Hooks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Login shortcode.
add_action( 'usercamp_login_shortcode_end', 'usercamp_add_form_id' );