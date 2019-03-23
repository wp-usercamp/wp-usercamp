<?php
/**
 * Template Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle redirects before content is output - hooked into template_redirect so is_page works.
 */
function uc_template_redirect() {
	global $wp_query, $wp;

	if ( isset( $wp->query_vars['logout'] ) ) {

		// Logout.
		wp_safe_redirect( str_replace( '&amp;', '&', wp_logout_url( uc_get_page_permalink( 'account' ) ) ) );
		exit;

	} elseif ( isset( $wp->query_vars['logout'] ) && 'true' === $wp->query_vars['logout'] ) {
		// Redirect to the correct logout endpoint.
		wp_safe_redirect( esc_url_raw( uc_get_account_endpoint_url( 'logout' ) ) );
		exit;

	}

}

/**
 * Get logout endpoint.
 */
function uc_logout_url( $redirect = '' ) {
	$redirect = $redirect ? $redirect : uc_get_page_permalink( 'account' );

	if ( get_option( 'usercamp_account_logout_endpoint' ) ) {
		return wp_nonce_url( uc_get_endpoint_url( 'logout', '', $redirect ), 'logout' );
	}

	return wp_logout_url( $redirect );
}

/**
 * Account navigation template.
 */
function usercamp_account_navigation() {
	uc_get_template( 'account/navigation.php' );
}

/**
 * Account content output.
 */
function usercamp_account_content() {
	global $wp;

	if ( ! empty( $wp->query_vars ) ) {
		foreach ( $wp->query_vars as $key => $value ) {
			// Ignore pagename param.
			if ( 'pagename' === $key ) {
				continue;
			}

			if ( has_action( 'usercamp_account_' . $key . '_endpoint' ) ) {
				do_action( 'usercamp_account_' . $key . '_endpoint', $value );
				return;
			}
		}
	}

	$has_form = uc_get_account_endpoint_form();
	if ( $has_form ) {
		uc_get_template( 'form/form.php', array(
			'atts'			=> array(),
			'the_form'		=> uc_get_form( $has_form ),
			'current_user' 	=> uc_get_user( get_current_user_id() ),
		) );
	}
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
 * Get form top note if available.
 */
function uc_form_loop_note( $args = array() ) {
	uc_get_template( 'loop/loop-note.php', $args );
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

	// Add font-size.
	if ( $the_form->font_size ) {
		$inline[] = 'font-size: ' . esc_attr( $the_form->font_size );
	}

	// Add max-width.
	if ( $the_form->max_width ) {
		$inline[] = 'max-width: ' . esc_attr( $the_form->max_width );
	}

	return apply_filters( 'uc_get_inline_styles', $inline, $the_form );
}

/**
 * Print inline style data.
 */
function uc_print_inline_styles() {
	if ( ! empty( $inline = uc_get_inline_styles() ) ) {
		echo 'style="' . implode( ';', $inline ) . ';"';
	}
}

/**
 * Display an animated checkmark.
 */
function uc_checkmark() {
	?>

	<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
		<circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
		<path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
	</svg>

	<?php
}

/**
 * Prepares custom field data.
 */
function uc_get_field( $array ) {
	global $the_form;

	if ( ! array_key_exists( 'type', ( array ) $array[ 'data' ] ) ) {
		return;
	}

	$mode					= $the_form->type;
	$field 					= $array['data'];
	$field_type				= uc_get_field_type( $field['type'] );
	$field['no_input']		= 0;
	$field['title_class'] 	= array();
	$field['label_class'] 	= array();
	$field['input_class'] 	= array();
	$field['field_class']	= array();
	$field['control_class']	= array();
	$field['attributes']	= array();

	// Get user input as value when submitting the form.
	$field['value'] = ( $the_form->is_request && isset( $the_form->postdata[ $field['key'] ] ) ) ? $the_form->postdata[ $field['key'] ] : '';

	// Get default icon.
	if ( empty( $field['icon'] ) ) {
		$field['icon'] = $field_type['icon'];
	}

	// For fields without input.
	if ( ! empty( $field_type['no_input'] ) ) {
		$field['no_input'] = 1;
	}

	// Classes.
	$field['field_class'][] = esc_attr( $field['key'] ) . '_field';
	if ( $the_form->has_error( $field['key'] ) ) {
		$field['label_class'][] = 'uc-error';
		$field['input_class'][] = 'uc-error';
	}
	if ( $the_form->icons == 'label' || ( $the_form->icons == 'inside' && $field['no_input'] ) ) {
		$field['title_class'][] = 'has-icon';
	}
	if ( $the_form->icons == 'inside' ) {
		$field['control_class'][] = 'has-icon';
	}

	// Edit label.
	if ( ! empty( $field['edit_label'] ) ) {
		$field['label'] = $field['edit_label'];
	}

	// Field attributes.
	if ( $mode == 'register' ) {
		if ( $field['key'] == 'user_login' ) {
			$field['attributes'][] = 'autocomplete=off';
		}
		if ( $field['type'] == 'password' ) {
			$field['attributes'][] = 'autocomplete=new-password';
		}
	}
	if ( ! empty( $field['placeholder'] ) ) {
		$field['attributes'][] = 'placeholder="' . esc_attr( $field['placeholder'] ) . '"';
	}

	/**
	 * Return the complete field and its attributes.
	 */
	return apply_filters( 'uc_get_field', $field, $the_form );

}