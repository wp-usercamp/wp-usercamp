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
 * Add the form ID as hidden input.
 */
function usercamp_add_form_id() {
	global $the_form;
	echo '<input type="hidden" name="_' . esc_attr( $the_form->type ) . '_id" id="_' . esc_attr( $the_form->type ) . '_id" value="' . absint( $the_form->id ) . '" />';
}

/**
 * Print inline style data.
 */
function uc_get_inline_styles() {
	global $the_form;
	$inline = array();

	// Add font-size to wrapper.
	if ( $the_form->font_size ) {
		$inline[] = 'font-size: ' . $the_form->font_size;
	}

	return apply_filters( 'uc_get_inline_styles', $inline, $the_form );
}

/**
 * Print inline style data.
 */
function uc_print_inline_styles() {
	if ( ! empty( $inline = uc_get_inline_styles() ) ) {
		echo 'style="' . implode( ';', $inline ) . '"';
	}
}

/**
 * Prepares custom field data.
 */
function uc_get_field( $array ) {
	global $the_form;

	$mode					= $the_form->type;
	$field 					= $array['data'];
	$field['title_class'] 	= array();
	$field['label_class'] 	= array();
	$field['input_class'] 	= array();
	$field['field_class']	= array();
	$field['control_class']	= array();
	$field['attributes']	= array();

	// Get user input as value when submitting the form.
	$field['value'] = ( $the_form->is_request && isset( $_REQUEST[ $field['key'] ] ) ) ? $_REQUEST[ $field['key'] ] : '';

	// Add error class where needed.
	if ( $the_form->has_error( $field['key'] ) ) {
		$field['label_class'][] = 'uc-error';
		$field['input_class'][] = 'uc-error';
	}

	// Add field key as a class by default.
	$field['field_class'][] = $field['key'] . '_field';

	// Forced attributes for register.
	if ( $mode == 'register' ) {
		if ( $field['key'] == 'user_login' ) {
			$field['attributes'][] = 'autocomplete=off';
		}
		if ( $field['type'] == 'password' ) {
			$field['attributes'][] = 'autocomplete=new-password';
		}
	}

	// Get default icon.
	if ( empty( $field['icon'] ) ) {
		$field['icon'] = uc_get_field_type( $field['type'], 'icon' );
	}

	// Add icon class to title.
	if ( $the_form->icons == 'label' ) {
		$field['title_class'][] = 'has-icon';
	}

	// Add icon class to element.
	if ( $the_form->icons == 'inside' ) {
		$field['control_class'][] = 'has-icon';
	}

	return apply_filters( 'uc_get_field', $field, $the_form );
}