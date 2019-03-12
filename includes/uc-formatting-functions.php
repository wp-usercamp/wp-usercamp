<?php
/**
 * Formatting Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 */
function uc_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'uc_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * Clean lowercase variables and use underscore instead of dash.
 */
function uc_sanitize_key( $var ) {
	return str_replace( '-', '_', sanitize_title_with_dashes( uc_clean( wp_unslash( $var ) ) ) );
}

/**
 * Format the endpoint slug, strip out anything not allowed in a url.
 */
function uc_sanitize_endpoint_slug( $raw_value ) {
	return sanitize_title( $raw_value );
}

/**
 * Sanitize a string destined to be a tooltip.
 */
function uc_sanitize_tooltip( $var ) {
	return htmlspecialchars(
		wp_kses(
			html_entity_decode( $var ), array(
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
				'small'  => array(),
				'span'   => array(),
				'ul'     => array(),
				'li'     => array(),
				'ol'     => array(),
				'p'      => array(),
			)
		)
	);
}

/**
 * Make a string lowercase.
 * Try to use mb_strtolower() when available.
 */
function uc_strtolower( $string ) {
	return function_exists( 'mb_strtolower' ) ? mb_strtolower( $string ) : strtolower( $string );
}

/**
 * Implode and escape HTML attributes for output.
 */
function uc_implode_html_attributes( $raw_attributes ) {
	$attributes = array();
	foreach ( $raw_attributes as $name => $value ) {
		$attributes[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}
	return implode( ' ', $attributes );
}