<?php
/**
 * Registers post types and taxonomies.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Post_Types class.
 */
class UC_Post_Types {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
	}

	/**
	 * Register core post types.
	 */
	public static function register_post_types() {
		if ( ! is_blog_installed() || post_type_exists( 'uc_form' ) ) {
			return;
		}

		do_action( 'usercamp_register_post_types' );

		register_post_type(
			'uc_form',
			apply_filters(
				'usercamp_register_post_type_form',
				array(
					'labels'             => array(
						'name'                  => __( 'Forms', 'usercamp' ),
						'singular_name'         => __( 'Form', 'usercamp' ),
						'menu_name'             => _x( 'Forms', 'Admin menu name', 'usercamp' ),
						'add_new'               => __( 'Add form', 'usercamp' ),
						'add_new_item'          => __( 'Add new form', 'usercamp' ),
						'edit'                  => __( 'Edit', 'usercamp' ),
						'edit_item'             => __( 'Edit form', 'usercamp' ),
						'new_item'              => __( 'New form', 'usercamp' ),
						'view_item'             => __( 'View form', 'usercamp' ),
						'search_items'          => __( 'Search forms', 'usercamp' ),
						'not_found'             => __( 'No forms found', 'usercamp' ),
						'not_found_in_trash'    => __( 'No forms found in trash', 'usercamp' ),
						'parent'                => __( 'Parent form', 'usercamp' ),
						'filter_items_list'     => __( 'Filter forms', 'usercamp' ),
						'items_list_navigation' => __( 'Forms navigation', 'usercamp' ),
						'items_list'            => __( 'Forms list', 'usercamp' ),
					),
					'description'         => __( 'This is where you can add new forms to customize your community pages.', 'usercamp' ),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'uc_form',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_in_menu'        => current_user_can( 'manage_usercamp' ) ? 'usercamp' : true,
					'hierarchical'        => false,
					'rewrite'             => false,
					'query_var'           => false,
					'supports'            => array( 'title' ),
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => true,
				)
			)
		);

		register_post_type(
			'uc_field',
			apply_filters(
				'usercamp_register_post_type_field',
				array(
					'labels'             => array(
						'name'                  => __( 'Custom fields', 'usercamp' ),
						'singular_name'         => __( 'Custom field', 'usercamp' ),
						'menu_name'             => _x( 'Custom fields', 'Admin menu name', 'usercamp' ),
						'add_new'               => __( 'Add custom field', 'usercamp' ),
						'add_new_item'          => __( 'Add new custom field', 'usercamp' ),
						'edit'                  => __( 'Edit', 'usercamp' ),
						'edit_item'             => __( 'Edit custom field', 'usercamp' ),
						'new_item'              => __( 'New custom field', 'usercamp' ),
						'view_item'             => __( 'View custom field', 'usercamp' ),
						'search_items'          => __( 'Search custom fields', 'usercamp' ),
						'not_found'             => __( 'No custom fields found', 'usercamp' ),
						'not_found_in_trash'    => __( 'No custom fields found in trash', 'usercamp' ),
						'parent'                => __( 'Parent custom field', 'usercamp' ),
						'filter_items_list'     => __( 'Filter custom fields', 'usercamp' ),
						'items_list_navigation' => __( 'Custom fields navigation', 'usercamp' ),
						'items_list'            => __( 'Custom fields list', 'usercamp' ),
					),
					'description'         => __( 'This is where you can manage custom fields.', 'usercamp' ),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'uc_field',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_in_menu'        => current_user_can( 'manage_usercamp' ) ? 'usercamp' : true,
					'hierarchical'        => false,
					'rewrite'             => false,
					'query_var'           => false,
					'supports'            => array( 'title' ),
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => true,
				)
			)
		);

		register_post_type(
			'uc_role',
			apply_filters(
				'usercamp_register_post_type_role',
				array(
					'labels'             => array(
						'name'                  => __( 'User roles', 'usercamp' ),
						'singular_name'         => __( 'User role', 'usercamp' ),
						'menu_name'             => _x( 'User roles', 'Admin menu name', 'usercamp' ),
						'add_new'               => __( 'Add user role', 'usercamp' ),
						'add_new_item'          => __( 'Add new user role', 'usercamp' ),
						'edit'                  => __( 'Edit', 'usercamp' ),
						'edit_item'             => __( 'Edit user role', 'usercamp' ),
						'new_item'              => __( 'New user role', 'usercamp' ),
						'view_item'             => __( 'View user role', 'usercamp' ),
						'search_items'          => __( 'Search user roles', 'usercamp' ),
						'not_found'             => __( 'No user roles found', 'usercamp' ),
						'not_found_in_trash'    => __( 'No user roles found in trash', 'usercamp' ),
						'parent'                => __( 'Parent user role', 'usercamp' ),
						'filter_items_list'     => __( 'Filter user roles', 'usercamp' ),
						'items_list_navigation' => __( 'User roles navigation', 'usercamp' ),
						'items_list'            => __( 'User roles list', 'usercamp' ),
					),
					'description'         => __( 'This is where you can add and manage your community roles.', 'usercamp' ),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'uc_role',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_in_menu'        => current_user_can( 'manage_usercamp' ) ? 'usercamp' : true,
					'hierarchical'        => false,
					'rewrite'             => false,
					'query_var'           => false,
					'supports'            => array( 'title' ),
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => true,
				)
			)
		);

		register_post_type(
			'uc_memberlist',
			apply_filters(
				'usercamp_register_post_type_memberlist',
				array(
					'labels'             => array(
						'name'                  => __( 'Member lists', 'usercamp' ),
						'singular_name'         => __( 'Member list', 'usercamp' ),
						'menu_name'             => _x( 'Member lists', 'Admin menu name', 'usercamp' ),
						'add_new'               => __( 'Add member list', 'usercamp' ),
						'add_new_item'          => __( 'Add new member list', 'usercamp' ),
						'edit'                  => __( 'Edit', 'usercamp' ),
						'edit_item'             => __( 'Edit member list', 'usercamp' ),
						'new_item'              => __( 'New member list', 'usercamp' ),
						'view_item'             => __( 'View member list', 'usercamp' ),
						'search_items'          => __( 'Search member lists', 'usercamp' ),
						'not_found'             => __( 'No member lists found', 'usercamp' ),
						'not_found_in_trash'    => __( 'No member lists found in trash', 'usercamp' ),
						'parent'                => __( 'Parent member list', 'usercamp' ),
						'filter_items_list'     => __( 'Filter member lists', 'usercamp' ),
						'items_list_navigation' => __( 'Member lists navigation', 'usercamp' ),
						'items_list'            => __( 'Member lists list', 'usercamp' ),
					),
					'description'         => __( 'This is where you can add and manage your community roles.', 'usercamp' ),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'uc_memberlist',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_in_menu'        => current_user_can( 'manage_usercamp' ) ? 'usercamp' : true,
					'hierarchical'        => false,
					'rewrite'             => false,
					'query_var'           => false,
					'supports'            => array( 'title' ),
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => true,
				)
			)
		);

		do_action( 'usercamp_after_register_post_types' );
	}

}

UC_Post_types::init();