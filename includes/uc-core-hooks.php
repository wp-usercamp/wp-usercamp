<?php
/**
 * Core Hooks.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * uc_field Internal meta keys
 */
add_filter( 'uc_field_meta_keys', 'uc_field_meta_keys', 10 );
function uc_field_meta_keys( $array ) {

	$array = array_merge( $array, array( 
		'key',
		'type',
		'icon',
		'label',
		'edit_label',
		'view_label',
		'placeholder',
		'helper',
		'can_view',
		'is_readonly',
		'is_private',
		'is_required',
		'dropdown_options',
		'checkbox_options',
		'radio_options',
		'blocked_emails',
		'allowed_emails',
		'is_crop',
		'crop_ratio',
		'vertical_crop',
		'max_image_size',
		'max_file_size',
		'error_hooks',
		'display_hooks',
		'filter_hooks',
		'presave_hooks',
		'postsave_hooks',
	) );

	return $array;
}