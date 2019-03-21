<?php
/**
 * User Role Core.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UC_Abstract_Post', false ) ) {
	include_once 'abstracts/abstract-class-uc-post.php';
}

/**
 * UC_Role class.
 */
class UC_Role extends UC_Abstract_Post {

	/**
	 * Post type.
	 */
	public $post_type = 'uc_role';

	/**
	 * Meta keys.
	 */
	public $internal_meta_keys = array(
		'name',
		'is_created',
		'caps',
		'label',
	);

	/**
	 * Get capability state.
	 */
	public function get_cap( $cap ) {
		$capabilities = ( array ) $this->caps;

		if ( ! array_key_exists( $cap, $capabilities ) ) {
			if ( $this->is_default( $cap ) && ! $this->is_created )
				return true;
			return false;
		}

		return $capabilities[ $cap ];
	}

	/**
	 * Set capabilities.
	 */
	public function set_caps( $caps ) {
		global $wp_roles;
		$this->caps = array();
		foreach( $caps as $cap => $bool ) {
			if ( $bool ) {
				$wp_roles->add_cap( $this->name, $cap );
				$this->caps[ $cap ] = uc_get_cap_title( $cap );
			} else {
				$wp_roles->remove_cap( $this->name, $cap );
				if ( array_key_exists( $cap, $this->caps ) ) {
					unset( $this->caps[ $cap ] );
				}
			}
		}
	}

	/**
	 * Check if capability on by default.
	 */
	public function is_default( $cap ) {
		return array_key_exists( $cap, uc_get_default_caps() );
	}

	/**
	 * Role exists.
	 */
	public function exists( $role ) {
		global $wp_roles;
		return ( in_array( $role, array_keys( usercamp_get_roles() ) ) || $wp_roles->is_role( $role ) );
	}

	/**
	 * Add New Role.
	 */
	public function add_new( $name, $title, $caps ) {
		usercamp_add_role( $name, $title, $caps );
	}

	/**
	 * Sync with previous role info.
	 */
	public function sync( $name ) {
		$wp_user_roles = get_option( 'wp_user_roles' );

		// Update WP roles database.
		$new_role = $wp_user_roles[ $this->name ];
		unset( $wp_user_roles[ $this->name ] );
		$wp_user_roles[ $name ] = $new_role;
		$wp_user_roles[ $name ]['name'] = uc_clean( $_POST['post_title'] );
		update_option( 'wp_user_roles', $wp_user_roles );

		// Change the role name in our own option.
		$allowed_roles = get_option( 'usercamp_roles' );
		if ( ( $key = array_search( $this->name, $allowed_roles ) ) !== false ) {
			unset( $allowed_roles[$key] );
			$allowed_roles[] = $name;
			update_option( 'usercamp_roles', $allowed_roles );
		}
	}

	/**
	 * When this item is deleted.
	 */
	public function _delete() {
		global $wp_roles;
		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		$allowed_roles 	= get_option( 'usercamp_roles' );
		$role 			= $this->name;

		if ( ! empty( $role ) && in_array( $role, array( 'member', 'community_manager' ) ) ) {
			return;
		}

		if ( ( $key = array_search( $role, $allowed_roles ) ) !== false ) {

			unset( $allowed_roles[$key] );

			foreach( array_merge( uc_get_default_caps(), uc_get_admin_caps(), uc_get_wp_admin_caps() ) as $cap => $value ) {
				$wp_roles->remove_cap( $role, $cap );
			}

			update_option( 'usercamp_roles', $allowed_roles );
			remove_role( $role );
		}
	}

}