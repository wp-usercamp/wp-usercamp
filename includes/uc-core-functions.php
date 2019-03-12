<?php
/**
 * Core Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include core functions (available in both admin and frontend).
require UC_ABSPATH . 'includes/uc-formatting-functions.php';
require UC_ABSPATH . 'includes/uc-file-functions.php';
require UC_ABSPATH . 'includes/uc-form-functions.php';
require UC_ABSPATH . 'includes/uc-field-functions.php';
require UC_ABSPATH . 'includes/uc-role-functions.php';
require UC_ABSPATH . 'includes/uc-memberlist-functions.php';

/**
 * Return a list of plugin specific post types.
 */
function uc_get_post_types() {
	return apply_filters( 'uc_get_post_types', array( 'uc_form', 'uc_field', 'uc_role', 'uc_memberlist' ) );
}

/**
 * Define a constant if it is not already defined.
 */
function uc_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

/**
 * Return the html selected attribute if stringified $value is found in array of stringified $options
 * or if stringified $value is the same as scalar stringified $options.
 */
function uc_selected( $value, $options ) {
	if ( is_array( $options ) ) {
		$options = array_map( 'strval', $options );
		return selected( in_array( (string) $value, $options, true ), true, false );
	}

	return selected( $value, $options, false );
}

/**
 * Display a Usercamp help tip.
 */
function uc_help_tip( $tip, $allow_html = false ) {
	if ( $allow_html ) {
		$tip = uc_sanitize_tooltip( $tip );
	} else {
		$tip = esc_attr( $tip );
	}

	return '<span class="usercamp-help-tip" data-tip="' . $tip . '"><i data-feather="help-circle"></i></span>';
}

/**
 * Get the date.
 */
function uc_get_the_date() {
	$format = apply_filters( 'uc_get_default_date_format', 'j/n/Y g:ia' );
	return get_the_date( $format );
}

/**
 * Array insert before a key.
 */
function uc_array_insert_before( $key, array &$array, $new_key, $new_value ) {
	if ( array_key_exists( $key, $array ) ) {
		$new = array();
		foreach ( $array as $k => $value ) {
			if ( $k === $key ) {
				$new[$new_key] = $new_value;
			}
			$new[$k] = $value;
		}
		return $new;
	}
	return false;
}

/**
 * Array insert after a key.
 */
function uc_array_insert_after( $key, array &$array, $new_key, $new_value ) {
	if ( array_key_exists( $key, $array ) ) {
		$new = array();
		foreach ( $array as $k => $value ) {
			$new[$k] = $value;
			if ( $k === $key ) {
				$new[$new_key] = $new_value;
			}
		}
		return $new;
	}
	return false;
}