<?php
/**
 * Admin Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get all screen ids.
 */
function uc_get_screen_ids() {

	$post_types = uc_get_post_types();
	$screen_ids = array();

	foreach( $post_types as $post_type ) {
		$screen_ids[] = "edit-{$post_type}";
		$screen_ids[] = $post_type;
	}

	return apply_filters( 'usercamp_screen_ids', $screen_ids );
}

/**
 * Get custom field settings on display.
 */
function uc_init_custom_field_options() {
	$metabox = new UC_Meta_Box_Field_Data();
	$metabox->output( new UC_Field() );
}

/**
 * For field setup inside modal.
 */
add_action( 'usercamp_before_general_field_options', 'usercamp_add_title_option', 10 );
function usercamp_add_title_option() {
	global $the_field;
	if ( get_post_type() != 'uc_field' ) {
		usercamp_wp_text_input(
			array(
				'id'          		=> 'label',
				'value'       		=> $the_field->label,
				'label'       		=> __( 'Label', 'usercamp' ),
				'description' 		=> __( 'Please enter a title for this custom field.', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);
	}
}

/**
 * Get comparison operators.
 */
function uc_get_comp_operators() {

	$array = array(
		'eq'		=> __( 'equals', 'usercamp' ),
		'gt'		=> __( 'more than', 'usercamp' ),
		'lt'		=> __( 'less than', 'usercamp' ),
		'between'	=> __( 'between', 'usercamp' ),
		'in'		=> __( 'in', 'usercamp' ),
		'before'	=> __( 'before', 'usercamp' ),
		'after'		=> __( 'after', 'usercamp' ),
		'contains'	=> __( 'contains', 'usercamp' ),
	);

	return apply_filters( 'uc_get_comp_operators', $array );
}