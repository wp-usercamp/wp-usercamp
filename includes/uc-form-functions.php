<?php
/**
 * Form Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a form.
 */
function uc_get_form( $form_id = '' ) {
	return new UC_Form( absint( $form_id ) );
}

/**
 * Get supported field types.
 */
function usercamp_get_form_types() {
	return apply_filters( 'usercamp_get_form_types', array(
		'register'		=>	array(
			'label'		=> __( 'Registration', 'usercamp' ),
			'icon'		=> 'user-plus',
		),
		'login'			=>	array(
			'label'		=> __( 'Login', 'usercamp' ),
			'icon'		=> 'lock',
		),
		'profile'		=>	array(
			'label'		=> __( 'Profile', 'usercamp' ),
			'icon'		=> 'user',
		),
		'account'		=> array(
			'label'		=> __( 'Account', 'usercamp' ),
			'icon'		=> 'settings',
		),
		'lostpassword'	=>	array(
			'label'		=> __( 'Lost Password', 'usercamp' ),
			'icon'		=> 'key',
		),
	) );
}

/**
 * Get form type name.
 */
function uc_get_form_type( $type ) {
	$types = usercamp_get_form_types();

	if ( ! isset( $types[ $type ] ) )
		return;

	return '<span class="uc-tag-icon"><i data-feather="' . $types[ $type ][ 'icon' ] . '"></i>' . $types[ $type ][ 'label' ] . '</span>';
}

/**
 * Get default forms.
 */
function usercamp_get_default_forms() {
	$array = array();

	array_push(
		$array,
		array(
			'type'		=> 'register',
			'title'		=> __( 'Registration', 'usercamp' ),
			'fields'	=> array(
				0		=> array( 'data' => usercamp_get_field( 'user_email' ), 	'row' => 1, 'col' => 1 ),
				1		=> array( 'data' => usercamp_get_field( 'user_login' ), 	'row' => 1, 'col' => 1 ),
				2		=> array( 'data' => usercamp_get_field( 'user_pass' ), 		'row' => 1, 'col' => 1 ),
			)
		) 
	);

	array_push(
		$array,
		array(
			'type'		=> 'login',
			'title'		=> __( 'Login', 'usercamp' ),
			'fields'	=> array(
				0		=> array( 'data' => usercamp_get_field( 'user_login' ), 	'row' => 1, 'col' => 1 ),
				1		=> array( 'data' => usercamp_get_field( 'user_pass' ), 		'row' => 1, 'col' => 1 ),
			)
		) 
	);

	array_push(
		$array,
		array(
			'type'		=> 'profile',
			'title'		=> __( 'Profile', 'usercamp' ),
			'fields'	=> array(
				1		=> array( 'data' => usercamp_get_field( 'display_name' ), 	'row' => 1, 'col' => 1 ),
				2		=> array( 'data' => usercamp_get_field( 'first_name' ), 	'row' => 1, 'col' => 1 ),
				3		=> array( 'data' => usercamp_get_field( 'last_name' ), 		'row' => 1, 'col' => 1 ),
			)
		)
	);

	array_push(
		$array,
		array(
			'type'		=> 'lostpassword',
			'title'		=> __( 'Lost Password', 'usercamp' ),
			'fields'	=> array(
				0		=> array( 'data' => usercamp_get_field( 'user_email' ), 	'row' => 1, 'col' => 1 ),
			)
		)
	);

	array_push(
		$array,
		array(
			'type'		=> 'account',
			'title'		=> __( 'Account - Main', 'usercamp' ),
			'fields'	=> array(
				0		=> array( 'data' => usercamp_get_field( 'user_login' ), 	'row' => 1, 'col' => 1 ),
				1		=> array( 'data' => usercamp_get_field( 'user_email' ), 	'row' => 1, 'col' => 1 ),
			),
			'endpoint'	=> 'edit-account',
		)
	);

	array_push(
		$array,
		array(
			'type'		=> 'account',
			'title'		=> __( 'Account - Password', 'usercamp' ),
			'fields'	=> array(
				1		=> array( 'data' => usercamp_get_field( 'user_pass' ), 		'row' => 1, 'col' => 1 ),
			),
			'endpoint'	=> 'edit-password',
		)
	);

	return apply_filters( 'usercamp_get_default_forms', $array );
}

/**
 * Create default forms.
 */
function usercamp_create_default_forms() {

	// Otherwise, forms will be empty!
	usercamp_create_default_fields();

	if ( ! empty( $forms = usercamp_get_default_forms() ) ) {
		foreach( $forms as $key => $data ) {

			$type = uc_clean( wp_unslash( $data[ 'type' ] ) );

			$the_form = new UC_Form();
			$the_form->set( 'post_title', isset( $data['title'] ) ? uc_clean( $data['title'] ) : '' );
			$the_form->set( 'post_name', sanitize_title( $the_form->post_title ) );
			$the_form->set( 'meta_input', array(
					'type'		=> $type,
					'fields'	=> isset( $data['fields'] ) ? uc_clean( $data['fields'] ) : '',
					'row_count'	=> 1,
					'cols'		=> array( 0 => array( 'count' => 0, 'layout' => 0 ), 1 => array( 'count' => 1, 'layout' => array( 0 => '100' ) ) ),
					'endpoint'	=> array_key_exists( 'endpoint', $data ) ? sanitize_title( $data['endpoint'] ) : '',
			) );
			$the_form->insert();
			$the_form->save( $the_form->meta_input );

			uc_create_default_form_once( $type, $the_form->id );
		}
	}

}

/**
 * Create a default form once in the database.
 */
function uc_create_default_form_once( $type = '', $id = '' ) {
	$form_option = 'usercamp_' . $type . '_form';
	if ( ! get_option( $form_option ) ) {
		update_option( $form_option, $id );
	}
}

/**
 * Get grid variations.
 */
function uc_get_form_grid() {

	$array = array(
		0	=> array( 'spans' => array( 100, 100 ) ),
		1	=> array( 'spans' => array( 50, 50 ) ),
		2	=> array( 'spans' => array( 33, 33, 33 ) ),
		3	=> array( 'spans' => array( 66, 33 ) ),
		4	=> array( 'spans' => array( 33, 66 ) ),
		5	=> array( 'spans' => array( 50, 25, 25 ) ),
		6	=> array( 'spans' => array( 25, 25, 50 ) ),
	);

	return apply_filters( 'uc_get_form_grid', $array );
}

/**
 * Get a form linked to a specific endpoint.
 */
function uc_get_endpoint_form( $endpoint ) {
	if ( ! $endpoint ) {
		return;
	}
	$form_id = get_option( 'usercamp_' . $endpoint . '_form', 0 );
	return apply_filters( 'uc_get_endpoint_form', $form_id );
}