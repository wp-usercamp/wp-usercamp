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
function uc_sanitize_title( $var ) {
	return str_replace( '-', '_', sanitize_title( wp_unslash( $var ) ) );
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

/**
 * Get the date.
 */
function uc_get_the_date() {
	$format = apply_filters( 'uc_get_default_date_format', 'j/n/Y g:ia' );
	return get_the_date( $format );
}