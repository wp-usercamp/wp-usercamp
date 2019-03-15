<?php
/**
 * Template Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get form loop editing part.
 */
function uc_form_loop_edit() {
	uc_get_template( 'loop/loop-edit.php' );
}

/**
 * Get form loop start part.
 */
function uc_form_loop_start() {
	uc_get_template( 'loop/loop-start.php' );
}

/**
 * Get form loop end part.
 */
function uc_form_loop_end() {
	uc_get_template( 'loop/loop-end.php' );
}

/**
 * Get form loop column part.
 */
function uc_form_loop_column( $args = array() ) {
	uc_get_template( 'loop/loop-column.php', $args );
}

/**
 * Prepares custom field data.
 */
function uc_get_field( $array ) {
	global $the_form;

	$field 					= $array['data'];
	$field['label_class'] 	= array();
	$field['input_class'] 	= array();

	// Force value if in $_REQUEST.
	$field['value'] = $the_form->is_request && isset( $_REQUEST[ $field['key'] ] ) ? $_REQUEST[ $field['key'] ] : '';

	// Add error class.
	if ( ! empty( $the_form->error_fields ) ) {
		if ( in_array( $field['key'], $the_form->error_fields ) ) {
			$field['label_class'][] = 'uc-error';
			$field['input_class'][] = 'uc-error';
		}
	}

	return apply_filters( 'uc_get_field', $field );
}