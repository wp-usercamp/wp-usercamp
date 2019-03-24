<?php
/**
 * Core Hooks.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * uc_form Internal meta keys
 */
add_filter( 'uc_form_meta_keys', 'uc_form_meta_keys', 10 );
function uc_form_meta_keys( $array ) {

	$array = array_merge( $array, array( 
		'type',
		'fields',
		'row_count',
		'cols',
		'use_ajax',
		'icons',
		'endpoint',
	) );

	return $array;
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

/**
 * uc_role Internal meta keys
 */
add_filter( 'uc_role_meta_keys', 'uc_role_meta_keys', 10 );
function uc_role_meta_keys( $array ) {

	$array = array_merge( $array, array( 
		'name',
		'is_created',
		'caps',
		'label',
	) );

	return $array;
}

/**
 * uc_memberlist Internal meta keys
 */
add_filter( 'uc_memberlist_meta_keys', 'uc_memberlist_meta_keys', 10 );
function uc_memberlist_meta_keys( $array ) {

	$array = array_merge( $array, array( 
		'per_page',
		'per_row',
		'login_required',
		'use_ajax',
		'roles',
		'rules',
		'search',
		'guest_search',
	) );

	return $array;
}