<?php
/**
 * Field Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get supported field types.
 */
function usercamp_get_field_types() {
	return apply_filters( 'usercamp_get_field_types', array(
		'text'			=>	array(
			'label'		=> __( 'Text Input', 'usercamp' ),
			'icon'		=> 'file-text',
		),
		'email'			=>	array(
			'label'		=> __( 'Email', 'usercamp' ),
			'icon'		=> 'mail',
		),
		'url'			=>	array(
			'label'		=> __( 'URL Address', 'usercamp' ),
			'icon'		=> 'link',
		),
		'phone'			=>	array(
			'label'		=> __( 'Phone Number', 'usercamp' ),
			'icon'		=> 'phone',
		),
		'password'		=>	array(
			'label'		=> __( 'Password', 'usercamp' ),
			'icon'		=> 'key',
		),
		'textarea'		=>	array(
			'label'		=> __( 'Textarea', 'usercamp' ),
			'icon'		=> 'clipboard',
		),
		'select'		=>	array(
			'label'		=> __( 'Dropdown', 'usercamp' ),
			'icon'		=> 'list',
		),
		'checkbox'		=>	array(
			'label'		=> __( 'Checkbox', 'usercamp' ),
			'icon'		=> 'square',
		),
		'radio'			=>	array(
			'label'		=> __( 'Radio', 'usercamp' ),
			'icon'		=> 'circle',
		),
		'toggle'		=>	array(
			'label'		=> __( 'Toggle', 'usercamp' ),
			'icon'		=> 'toggle-left',
			'no_input'	=> 1,
		),
		'image'			=>	array(
			'label'		=> __( 'Image Upload', 'usercamp' ),
			'icon'		=> 'image',
		),
		'file'			=>	array(
			'label'		=> __( 'File Upload', 'usercamp' ),
			'icon'		=> 'file',
		),
		'rating'		=>	array(
			'label'		=> __( 'Rating', 'usercamp' ),
			'icon'		=> 'star',
		),
		'date'			=> array(
			'label'		=>	__( 'Date Picker', 'usercamp' ),
			'icon'		=> 'calendar',
		),
		'dynamic'		=> array(
			'label'		=>	__( 'Dynamic', 'usercamp' ),
			'icon'		=> 'code',
		),
	) );
}

/**
 * Get field type name.
 */
function uc_get_field_type( $type, $return = false ) {
	$types = usercamp_get_field_types();

	if ( ! isset( $types[ $type ] ) )
		return;

	if ( $return == 'label' ) {
		return $types[ $type ][ 'label' ];
	}

	if ( $return == 'icon' ) {
		return $types[ $type ][ 'icon' ];
	}

	if ( $return == 'html' ) {
		return '<span class="uc-tag-icon"><i data-feather="' . $types[ $type ][ 'icon' ] . '"></i>' . $types[ $type ][ 'label' ] . '</span>';
	}

	return $types[ $type ];
}

/**
 * Get custom fields.
 */
function usercamp_get_custom_fields() {
	return apply_filters( 'usercamp_get_custom_fields', get_option( 'usercamp_fields' ) );
}

/**
 * Get a custom field from options.
 */
function usercamp_get_field( $key ) {
	$array = (array) get_option( 'usercamp_fields' );
	return array_key_exists( $key, $array ) ? $array[$key] : '';
}

/**
 * Get default fields.
 */
function usercamp_get_default_fields() {
	return apply_filters( 'usercamp_get_default_fields', array(
		'user_login'		=>	array(
			'label'			=> __( 'Username', 'usercamp' ),
			'type'			=> 'text',
			'icon'			=> 'user',
			'is_private'	=> 1,
			'is_readonly'	=> 1,
			'can_view'		=> array( 'owner', 'administrator', 'community_manager' ),
		),
		'user_email'		=>	array(
			'label'			=> __( 'Email Address', 'usercamp' ),
			'type'			=> 'email',
			'is_private'	=> 1,
			'can_view'		=> array( 'owner' ),
		),
		'first_name'		=>	array(
			'label'			=> __( 'First Name', 'usercamp' ),
			'type'			=> 'text',
			'is_private'	=> 1,
			'can_view'		=> array( 'owner' ),
		),
		'last_name'			=>	array(
			'label'			=> __( 'Last Name', 'usercamp' ),
			'type'			=> 'text',
			'is_private'	=> 1,
			'can_view'		=> array( 'owner' ),
		),
		'display_name'		=>	array(
			'label'			=> __( 'Display Name', 'usercamp' ),
			'type'			=> 'text',
		),
		'description'		=>	array(
			'label'			=> __( 'Biography', 'usercamp' ),
			'type'			=> 'textarea',
		),
		'user_url'			=>	array(
			'label'			=> __( 'Website URL', 'usercamp' ),
			'type'			=> 'url',
		),
		'user_registered'	=>	array(
			'label'			=> __( 'Registration Date', 'usercamp' ),
			'type'			=> 'dynamic',
			'is_readonly'	=> 1,
		),
		'user_pass'			=> array(
			'label'			=> __( 'Password', 'usercamp' ),
			'type'			=> 'password',
			'icon'			=> 'lock',
			'is_private'	=> 1,
			'can_view'		=> array( '_none' ),
		),
	) );
}

/**
 * Create default fields.
 */
function usercamp_create_default_fields() {

	if ( ! empty( $fields = usercamp_get_default_fields() ) ) {
		foreach( $fields as $key => $data ) {
			$field = new UC_Field();
			$field->set( 'post_title', isset( $data['label'] ) ? $data['label'] : '' );
			$field->set( 'post_name', uc_clean( wp_unslash( $key ) ) );
			$field->set( 'meta_input', array(
					'key'			=> uc_clean( wp_unslash( $key ) ),
					'label'			=> isset( $data['label'] ) ? $data['label'] : '',
					'type'			=> isset( $data['type'] ) ? $data['type'] : '',
					'is_private'	=> isset( $data['is_private'] ) ? $data['is_private'] : '',
					'is_readonly'	=> isset( $data['is_readonly'] ) ? $data['is_readonly'] : '',
					'is_required'	=> isset( $data['is_required'] ) ? $data['is_required'] : '',
					'can_view'		=> isset( $data['can_view'] ) ? $data['can_view'] : '',
					'icon'			=> isset( $data['icon'] ) ? $data['icon'] : '',
			) );
			$field->insert();
			$field->save( $field->meta_input );
		}
	}

}

/**
 * Remove default fields.
 */
function usercamp_remove_default_fields() {
	global $wpdb;

	if ( ! current_user_can( 'delete_uc_fields' ) ) {
		wp_die( -1 );
	}

	if ( ! empty( $fields = usercamp_get_default_fields() ) ) {
		$usercamp_fields = get_option( 'usercamp_fields' );

		foreach( $fields as $key => $data ) {

			if ( isset( $usercamp_fields[ $key ] ) ) {
				unset( $usercamp_fields[ $key ] );
			}

			$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'uc_field' AND post_name = '{$key}' AND 1=1" );
		}

		$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

		if ( ! empty( $usercamp_fields ) ) {
			update_option( 'usercamp_fields', $usercamp_fields );
		} else {
			delete_option( 'usercamp_fields' );
		}

	}

}

/**
 * Return a list of field option names.
 */
function uc_get_field_opts() {

	$array = array( 
		'key',
		'type',
		'icon',
		'label',
		'edit_label',
		'view_label',
		'placeholder',
		'help',
		'can_view',
		'is_readonly',
		'is_private',
		'is_required',
		'dropdown_options',
		'checkbox_options',
		'radio_options',
		'blocked_emails',
		'allowed_emails',
		'is_crop',
		'crop_ratio',
		'vertical_crop',
		'max_image_size',
		'max_file_size',
		'error_hooks',
		'display_hooks',
		'filter_hooks',
		'presave_hooks',
		'postsave_hooks',
	);

	return apply_filters( 'uc_get_field_opts', $array );
}

/**
 * Get field data attributes.
 */
function uc_get_data_attributes( $data ) {
	$output = '';
	foreach( $data as $key => $value ) {
		if ( is_array( $value ) ) {
			$value = implode( ',', $value );
		} else {
			$value = esc_attr( $value );
		}
		$output .= ' data-' . esc_attr( $key ) . '="' . $value . '"';
	}
	$output .= ' data-noicon="' . esc_attr( uc_get_field_type( $data['type'], 'icon' ) ) . '"';
	return $output;
}