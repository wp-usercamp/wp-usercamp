<?php
/**
 * Role Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add a WordPress role.
 */
function usercamp_add_role( $role = '', $label = '', $capabilities = '' ) {
	if ( ! $label ) {
		$label = ucfirst( $role );
	}

	add_role( $role, $label, $capabilities );

	// update current plugin roles.
	$current_roles = get_option( 'usercamp_roles' );

	if ( ! in_array( $role, (array) $current_roles ) ) {
		$current_roles[] = $role;
		update_option( 'usercamp_roles', $current_roles );
	}

}

/**
 * Get a WordPress role.
 */
function usercamp_get_role( $role = '' ) {
	global $wp_roles;

	if ( ! class_exists( 'WP_Roles' ) ) {
		return;
	}

	if ( ! isset( $wp_roles ) ) {
		$wp_roles = new WP_Roles();
	}

	return $wp_roles->get_role( $role );
}

/**
 * Assign a specific role to a user.
 */
function usercamp_set_role( $user_id, $role ) {
	global $current_user;

	if ( ! class_exists( 'WP_User' ) ) {
		return;
	}

	if ( $user_id == 1 ) {
		return;
	}

	if ( $current_user->ID == 1 ) {
		$u = new WP_User( $user_id );
		$u->set_role( $role );
	} else {
		$current_user->set_role( $role );
	}

}

/**
 * Get a list of available WP roles.
 */
function usercamp_get_roles() {
    global $wp_roles;

	$roles = array();

    foreach( $wp_roles->roles as $role => $data ) {
		$roles[ $role ] = $data[ 'name' ];
	}

	return apply_filters( 'usercamp_get_roles', $roles );
}

/**
 * Get a list of plugin roles only.
 */
function uc_get_roles() {
	return apply_filters( 'uc_get_roles', get_option( 'usercamp_roles' ) );
}

/**
 * Get default capabilities.
 */
function uc_get_default_caps() {

	$array = array(
		'uc_edit_profile'		 	=> true,
		'uc_view_profiles'			=> true,
		'uc_view_memberlist'		=> true,
		'uc_search_memberlist'		=> true,
		'uc_log_in'					=> true,
	);

	return apply_filters( 'uc_get_default_caps', $array );
}

/**
 * Get admin capabilities.
 */
function uc_get_admin_caps() {

	$capabilities = array(
		'uc_view_private'			=> true,
		'uc_view_private_data'		=> true,
		'uc_edit_users'				=> true,
		'uc_delete_users'			=> true,
		'uc_alter_users'			=> true,
		'uc_access_wpadmin'			=> true,
		'uc_settings'				=> true,
		'uc_addons'					=> true,
		'manage_usercamp'			=> true,
	);

	$capability_types = uc_get_post_types();

	foreach ( $capability_types as $capability_type ) {
		$capabilities = $capabilities + array(
			"edit_{$capability_type}" 						=> true,
			"read_{$capability_type}" 						=> true,
			"delete_{$capability_type}" 					=> true,
			"edit_{$capability_type}s" 						=> true,
			"edit_others_{$capability_type}s" 				=> true,
			"publish_{$capability_type}s" 					=> true,
			"read_private_{$capability_type}s" 				=> true,
			"delete_{$capability_type}s" 					=> true,
			"delete_private_{$capability_type}s" 			=> true,
			"delete_published_{$capability_type}s" 			=> true,
			"delete_others_{$capability_type}s" 			=> true,
			"edit_private_{$capability_type}s" 				=> true,
			"edit_published_{$capability_type}s" 			=> true,
		);
	}

	return apply_filters( 'uc_get_admin_caps', $capabilities );
}

/**
 * Get WP admin capabilities.
 */
function uc_get_wp_admin_caps() {

	$array = array(
		'read'                   => true,
		'level_9'                => true,
		'level_8'                => true,
		'level_7'                => true,
		'level_6'                => true,
		'level_5'                => true,
		'level_4'                => true,
		'level_3'                => true,
		'level_2'                => true,
		'level_1'                => true,
		'level_0'                => true,
		'read_private_pages'     => true,
		'read_private_posts'     => true,
		'edit_posts'             => true,
		'edit_pages'             => true,
		'edit_published_posts'   => true,
		'edit_published_pages'   => true,
		'edit_private_pages'     => true,
		'edit_private_posts'     => true,
		'edit_others_posts'      => true,
		'edit_others_pages'      => true,
		'publish_posts'          => true,
		'publish_pages'          => true,
		'delete_posts'           => true,
		'delete_pages'           => true,
		'delete_private_pages'   => true,
		'delete_private_posts'   => true,
		'delete_published_pages' => true,
		'delete_published_posts' => true,
		'delete_others_posts'    => true,
		'delete_others_pages'    => true,
		'manage_categories'      => true,
		'manage_links'           => true,
		'moderate_comments'      => true,
		'upload_files'           => true,
		'export'                 => true,
		'import'                 => true,
		'list_users'             => true,
		'edit_theme_options'     => true,
	);

	return apply_filters( 'uc_get_wp_admin_caps', $array );
}

/**
 * Get capability titles.
 */
function uc_get_cap_titles() {

	$array = array(
		'uc_edit_profile'			=> __( 'Edit profile', 'usercamp' ),
		'uc_log_in'					=> __( 'Login to site', 'usercamp' ),
		'uc_view_profiles'			=> __( 'View profiles', 'usercamp' ),
		'uc_view_private'			=> __( 'View private profiles', 'usercamp' ),
		'uc_view_private_data'		=> __( 'View private info', 'usercamp' ),
		'uc_edit_users'				=> __( 'Edit users', 'usercamp' ),
		'uc_alter_users'			=> __( 'Modify users', 'usercamp' ),
		'uc_delete_users'			=> __( 'Delete users', 'usercamp' ),
		'uc_access_wpadmin'			=> __( 'View wp-admin', 'usercamp' ),
		'uc_view_memberlist'		=> __( 'View member lists', 'usercamp' ),
		'uc_search_memberlist'		=> __( 'Search member lists', 'usercamp' ),
		'manage_usercamp'			=> __( 'Community manager', 'usercamp' ),
		'uc_settings'				=> __( 'Edit community settings', 'usercamp' ),
		'uc_addons'					=> __( 'Manage community add-ons', 'usercamp' ),
		'publish_uc_forms'			=> __( 'Create forms', 'usercamp' ),
		'publish_uc_fields'			=> __( 'Create custom fields', 'usercamp' ),
		'publish_uc_roles'			=> __( 'Create user roles', 'usercamp' ),
		'publish_uc_memberlists'	=> __( 'Create member lists', 'usercamp' ),
		'edit_uc_forms'				=> __( 'Manage forms', 'usercamp' ),
		'edit_uc_fields'			=> __( 'Manage custom fields', 'usercamp' ),
		'edit_uc_roles'				=> __( 'Manage user roles', 'usercamp' ),
		'edit_uc_memberlists'		=> __( 'Manage member lists', 'usercamp' ),
		'delete_uc_forms'			=> __( 'Delete forms', 'usercamp' ),
		'delete_uc_fields'			=> __( 'Delete custom fields', 'usercamp' ),
		'delete_uc_roles'			=> __( 'Delete user roles', 'usercamp' ),
		'delete_uc_memberlists'		=> __( 'Delete member lists', 'usercamp' ),
	);

	return apply_filters( 'uc_get_cap_titles', $array );
}

/**
 * Get a nice display title for a given capability.
 */
function uc_get_cap_title( $cap ) {

	$array = uc_get_cap_titles();
	$title = isset( $array[ $cap ] ) ? uc_clean( $array[ $cap ] ) : '';

	return apply_filters( 'uc_get_cap_title', $title, $cap );
}

/**
 * Create default user roles.
 */
function usercamp_create_default_roles() {
	global $wp_roles;

	if ( ! class_exists( 'WP_Roles' ) ) {
		return;
	}

	if ( ! isset( $wp_roles ) ) {
		$wp_roles = new WP_Roles();
	}

	foreach( $wp_roles->roles as $key => $data ) {
		$the_role = new UC_Role();
		$the_role->set( 'post_title', array_key_exists( 'name', $data ) ? $data['name'] : '' );
		$the_role->set( 'post_name', uc_clean( wp_unslash( $key ) ) );
		$the_role->set( 'meta_input', array(
				'name'			=> $key,
				'is_created'	=> 1,
				'caps'			=> array_intersect_key( uc_get_cap_titles(), $data['capabilities'] ) + $data['capabilities'],
		) );
		$the_role->insert();
		$the_role->save( $the_role->meta_input );
	}

}