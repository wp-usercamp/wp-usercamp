<?php
/**
 * Core Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include core functions (available in both admin and frontend).
require UC_ABSPATH . 'includes/uc-conditional-functions.php';
require UC_ABSPATH . 'includes/uc-formatting-functions.php';
require UC_ABSPATH . 'includes/uc-file-functions.php';
require UC_ABSPATH . 'includes/uc-form-functions.php';
require UC_ABSPATH . 'includes/uc-field-functions.php';
require UC_ABSPATH . 'includes/uc-role-functions.php';
require UC_ABSPATH . 'includes/uc-memberlist-functions.php';
require UC_ABSPATH . 'includes/uc-page-functions.php';
require UC_ABSPATH . 'includes/uc-account-functions.php';
require UC_ABSPATH . 'includes/uc-user-functions.php';

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
 * Display a help tip.
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

/**
 * Get template part.
 */
function uc_get_template_part( $slug, $name = '' ) {
	global $the_form;

	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/usercamp/slug-name.php.
	if ( $name && ! UC_TEMPLATE_DEBUG_MODE ) {
		$template = locate_template( array( "{$slug}-{$name}.php", uc()->template_path() . "{$slug}-{$name}.php" ) );
	}

	// Get default slug-name.php.
	if ( ! $template && $name && file_exists( uc()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
		$template = uc()->plugin_path() . "/templates/{$slug}-{$name}.php";
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/usercamp/slug.php.
	if ( ! $template && ! UC_TEMPLATE_DEBUG_MODE ) {
		$template = locate_template( array( "{$slug}.php", uc()->template_path() . "{$slug}.php" ) );
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'uc_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 */
function uc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	global $the_form;

	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$located = uc_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'uc_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'usercamp_before_template_part', $template_name, $template_path, $located, $args );

	include $located;

	do_action( 'usercamp_after_template_part', $template_name, $template_path, $located, $args );
}


/**
 * Like uc_get_template, but returns the HTML instead of outputting.
 */
function uc_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	global $the_form;

	ob_start();
	uc_get_template( $template_name, $args, $template_path, $default_path );
	return ob_get_clean();
}
/**
 * Locate a template and return the path for inclusion.
 */
function uc_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	global $the_form;

	if ( ! $template_path ) {
		$template_path = uc()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = uc()->plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template/.
	if ( ! $template || UC_TEMPLATE_DEBUG_MODE ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'usercamp_locate_template', $template, $template_name, $template_path );
}