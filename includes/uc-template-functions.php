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
	return apply_filters( 'uc_get_field', $array['data'] );
}