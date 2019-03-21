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

/**
 * Create a page and store the ID in an option.
 */
function uc_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	$option_value = get_option( $option );

	if ( $option_value > 0 && ( $page_object = get_post( $option_value ) ) ) {
		if ( 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ) ) ) {
			// Valid page is already in place
			return $page_object->ID;
		}
	}

	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
	}

	$valid_page_found = apply_filters( 'usercamp_create_page_id', $valid_page_found, $slug, $page_content );

	if ( $valid_page_found ) {
		if ( $option ) {
			update_option( $option, $valid_page_found );
		}
		return $valid_page_found;
	}

	// Search for a matching valid trashed page
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'          => $page_id,
			'post_status' => 'publish',
		);
		wp_update_post( $page_data );
	} else {
		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed',
		);
		$page_id   = wp_insert_post( $page_data );
	}

	if ( $option ) {
		update_option( $option, $page_id );
	}

	return $page_id;
}