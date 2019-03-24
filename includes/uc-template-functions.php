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

			// Action hooks has the first priority.
			if ( has_action( 'usercamp_account_' . $key . '_endpoint' ) ) {
				do_action( 'usercamp_account_' . $key . '_endpoint', $value );
				return;
			}
		}
	}

	// If we do not have an action hook, load the respective form.
	$has_form = uc_get_account_endpoint_form();
	if ( $has_form ) {
		uc_get_template( 'form/form.php', array(
			'atts'			=> array(
				'first_button' => __( 'Save changes', 'usercamp' ),
			),
			'the_form'		=> uc_get_form( $has_form ),
			'current_user' 	=> uc_get_user( get_current_user_id() ),
		) );
	}
}

/**
 * Get form edit template.
 */
function uc_form_edit( $args = array() ) {
	uc_get_template( 'form/form-edit.php', $args );
}

/**
 * Get form row start part.
 */
function uc_form_row_start( $args = array() ) {
	uc_get_template( 'form/form-row-start.php', $args );
}

/**
 * Get form row end part.
 */
function uc_form_row_end( $args = array() ) {
	uc_get_template( 'form/form-row-end.php', $args );
}

/**
 * Get form column part.
 */
function uc_form_column( $args = array() ) {
	uc_get_template( 'form/form-column.php', $args );
}

/**
 * Get form top note if available.
 */
function uc_form_note( $args = array() ) {
	uc_get_template( 'form/form-note.php', $args );
}

/**
 * Get form buttons.
 */
function uc_form_buttons( $args = array() ) {
	uc_get_template( 'form/form-buttons.php', $args );
}

/**
 * Add the hidden inputs including nonce.
 */
function usercamp_add_form_inputs() {
	global $the_form;

	if ( ! empty( uc()->query->get_current_endpoint() ) ) {
		$endpoint = uc()->query->get_current_endpoint();
	} else {
		$endpoint = esc_attr( $the_form->type );
	}

	if ( $endpoint == 'account' ) {
		$endpoint = uc_get_account_default_endpoint();
	}

	// Allow the endpoint for this nonce to be filtered.
	$endpoint = apply_filters( 'usercamp_get_nonce_endpoint', $endpoint, $the_form );

	echo '<input type="hidden" name="_' . $endpoint . '_id" id="_' . $endpoint . '_id" value="' . absint( $the_form->id ) . '" />';

	wp_nonce_field( 'usercamp-' . $endpoint, 'usercamp-' . $endpoint . '-nonce' );
}

/**
 * Display the endpoint title above account content.
 */
function usercamp_show_endpoint_title() {
	$endpoint       = uc()->query->get_current_endpoint();
	if ( ! $endpoint ) {
		$endpoint = uc_get_account_default_endpoint();
	}
	$endpoint_title = uc()->query->get_endpoint_title( $endpoint );
	$endpoint_desc	= uc()->query->get_endpoint_desc( $endpoint );
	?>
	<h3>
		<?php esc_html_e( $endpoint_title ); ?>
		<?php if ( ! empty( $endpoint_desc ) ) : ?>
		<span><?php echo esc_html( $endpoint_desc ); ?></span>
		<?php endif; ?>
	</h3>
	<?php
}

/**
 * Print inline style data.
 */
function uc_get_inline_styles() {
	global $the_form;
	$inline = array();

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
	if ( $field['key'] == 'user_login' && $mode != 'login' ) {
		$field['attributes'][] = 'autocomplete=off';
	}
	if ( $field['type'] == 'password' && $mode != 'login' ) {
		$field['attributes'][] = 'autocomplete=new-password';
	}
	if ( ! empty( $field['placeholder'] ) ) {
		$field['attributes'][] = 'placeholder="' . esc_attr( $field['placeholder'] ) . '"';
	}

	/**
	 * Add some filters to the field output.
	 */
	$field = apply_filters( 'uc_get_field', $field, $the_form );
	$field = apply_filters( 'uc_get_' . esc_attr( $field['key'] ) . '_field', $field, $the_form );
	$field = apply_filters( 'uc_get_' . esc_attr( $the_form->type ) . '_' . esc_attr( $field['key'] ) . '_field', $field, $the_form );

	return $field;
}

/**
 * Edit password endpoint - create add password fields automatically.
 */
function uc_add_new_password_fields( $fields, $the_form, $row, $col ) {
	if ( $the_form->endpoint != 'edit-password' ) {
		return;
	}

	$item = uc_array_search( 'user_pass', $fields );
	if ( is_array( $item ) ) {
		$item[ 'data' ][ 'key' ] = 'new_password';
		array_push( $fields, $item );
		$item[ 'data' ][ 'key' ] = 'verify_new_password';
		array_push( $fields, $item );
	}

	return $fields;
}

/**
 * Current user password.
 */
function uc_get_account_user_pass_field( $field, $the_form ) {

	$field['label'] 		= __( 'Current password', 'usercamp' );
	$field['helper'] 		= '<a href="#">' . __( 'Forgot your password?', 'usercamp' ) . '</a>';
	$field['hide_toggle'] 	= true;

	return $field;
}

/**
 * New user password.
 */
function uc_get_account_new_password_field( $field, $the_form ) {

	$field['label'] 		= __( 'New password', 'usercamp' );
	$field['hide_toggle'] 	= true;

	return $field;
}

/**
 * Verify user password.
 */
function uc_get_account_verify_new_password_field( $field, $the_form ) {

	$field['label'] 		= __( 'Verify password', 'usercamp' );
	$field['hide_toggle'] 	= true;

	return $field;
}