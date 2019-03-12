<?php
/**
 * User role data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Role_Data Class.
 */
class UC_Meta_Box_Role_Data {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_role;

		$thepostid      = $post->ID;
		$the_role 		= $thepostid ? new UC_Role( $thepostid ) : new UC_Role();

		wp_nonce_field( 'usercamp_save_data', 'usercamp_meta_nonce' );

		include 'views/html-role-data-panel.php';
	}

	/**
	 * Return array of tabs to show.
	 */
	private static function get_tabs() {
		$tabs = apply_filters(
			'usercamp_role_data_tabs', array(
				'general'        => array(
					'icon'	   => 'settings',
					'label'    => __( 'General', 'usercamp' ),
					'target'   => 'general_role_data',
					'class'    => array(),
					'priority' => 10,
				),
				'access'        => array(
					'icon'	   => 'lock',
					'label'    => __( 'Access', 'usercamp' ),
					'target'   => 'access_role_data',
					'class'    => array(),
					'priority' => 20,
				),
				'admin'        => array(
					'icon'	   => 'shield',
					'label'    => __( 'Admin', 'usercamp' ),
					'target'   => 'admin_role_data',
					'class'    => array(),
					'priority' => 30,
				),
				'forms'        => array(
					'icon'	   => 'file-text',
					'label'    => __( 'Forms', 'usercamp' ),
					'target'   => 'forms_role_data',
					'class'    => array(),
					'priority' => 40,
				),
				'fields'       => array(
					'icon'	   => 'database',
					'label'    => __( 'Custom fields', 'usercamp' ),
					'target'   => 'fields_role_data',
					'class'    => array(),
					'priority' => 50,
				),
				'roles'        => array(
					'icon'	   => 'user-check',
					'label'    => __( 'User roles', 'usercamp' ),
					'target'   => 'roles_role_data',
					'class'    => array(),
					'priority' => 60,
				),
				'memberlists'  => array(
					'icon'	   => 'users',
					'label'    => __( 'Member lists', 'usercamp' ),
					'target'   => 'memberlists_role_data',
					'class'    => array(),
					'priority' => 70,
				),
			)
		);

		// Sort tabs based on priority.
		uasort( $tabs, array( __CLASS__, 'tabs_sort' ) );

		return $tabs;
	}

	/**
	 * Callback to sort data tabs on priority.
	 */
	private static function tabs_sort( $a, $b ) {
		if ( ! isset( $a['priority'], $b['priority'] ) ) {
			return -1;
		}

		if ( $a['priority'] == $b['priority'] ) {
			return 0;
		}

		return $a['priority'] < $b['priority'] ? -1 : 1;
	}

	/**
	 * Show tab content/settings.
	 */
	private static function output_tabs() {
		global $post, $thepostid, $the_role;

		include 'views/html-role-data-general.php';
		include 'views/html-role-data-admin.php';
		include 'views/html-role-data-access.php';
		include 'views/html-role-data-forms.php';
		include 'views/html-role-data-fields.php';
		include 'views/html-role-data-roles.php';
		include 'views/html-role-data-memberlists.php';
	}

	/**
	 * Save meta box data.
	 */
	public static function save( $post_id, $post ) {
		global $the_role;
		$props = array();

		$the_role = new UC_Role( $post_id );
		$name = uc_sanitize_key( uc_clean( wp_unslash( $_POST['post_title'] ) ) );

		// New user role.
		if ( ! $the_role->is_created ) {
			if ( $the_role->exists( $name ) ) {
				UC_Admin_Meta_Boxes::add_error( __( '<strong>Your changes have not been saved.</strong> A user role with the same name already exists in the system.', 'usercamp' ) );
			} else {
				$the_role->add_new( $name, uc_clean( $_POST['post_title'] ), uc_get_default_caps() );
				$props['name'] = $name;
				$props['is_created'] = 1;
			}
		} else {
			if ( uc_clean( wp_unslash( $_POST['post_title'] ) ) != uc_clean( wp_unslash( $_POST['original_post_title'] ) ) ) {
				if ( $the_role->exists( $name ) ) {
					UC_Admin_Meta_Boxes::add_error( __( '<strong>Your changes have not been saved.</strong> A user role with the same name already exists in the system.', 'usercamp' ) );
				} else {
					$the_role->sync( $name );
					$props['name'] = $name;
					$props['is_created'] = 1;
				}
			}
		}

		// Set properties.
		$the_role->set( 'name', $name );

		// Set capabilities.
		$the_role->set_caps( array(
			'uc_edit_profile' 			=> ! empty( $_POST['uc_edit_profile'] ),
			'uc_view_profiles' 			=> ! empty( $_POST['uc_view_profiles'] ),
			'uc_view_private' 			=> ! empty( $_POST['uc_view_private'] ),
			'uc_view_private_data' 		=> ! empty( $_POST['uc_view_private_data'] ),
			'uc_view_memberlist' 		=> ! empty( $_POST['uc_view_memberlist'] ),
			'uc_search_memberlist'		=> ! empty( $_POST['uc_search_memberlist'] ),
			'uc_edit_users'				=> ! empty( $_POST['uc_edit_users'] ),
			'uc_delete_users'			=> ! empty( $_POST['uc_delete_users'] ),
			'uc_alter_users'			=> ! empty( $_POST['uc_alter_users'] ),
			'uc_access_wpadmin'			=> ! empty( $_POST['uc_access_wpadmin'] ),
			'uc_log_in'					=> ! empty( $_POST['uc_log_in'] ),
			'uc_settings'				=> ! empty( $_POST['uc_settings'] ),
			'uc_addons'					=> ! empty( $_POST['uc_addons'] ),
			'manage_usercamp'			=> ! empty( $_POST['manage_usercamp'] ),
			'publish_uc_forms'			=> ! empty( $_POST['publish_uc_forms'] ),
			'publish_uc_fields'			=> ! empty( $_POST['publish_uc_fields'] ),
			'publish_uc_roles'			=> ! empty( $_POST['publish_uc_roles'] ),
			'publish_uc_memberlists'	=> ! empty( $_POST['publish_uc_memberlists'] ),
			'edit_uc_forms'				=> ! empty( $_POST['edit_uc_forms'] ),
			'edit_uc_fields'			=> ! empty( $_POST['edit_uc_fields'] ),
			'edit_uc_roles'				=> ! empty( $_POST['edit_uc_roles'] ),
			'edit_uc_memberlists'		=> ! empty( $_POST['edit_uc_memberlists'] ),
			'delete_uc_forms'			=> ! empty( $_POST['delete_uc_forms'] ),
			'delete_uc_fields'			=> ! empty( $_POST['delete_uc_fields'] ),
			'delete_uc_roles'			=> ! empty( $_POST['delete_uc_roles'] ),
			'delete_uc_memberlists'		=> ! empty( $_POST['delete_uc_memberlists'] ),
		) );

		// Set props.
		$props['caps'] 	= $the_role->caps;
		$props['label'] = uc_clean( wp_unslash( $_POST['post_title'] ) );

		// Save.
		$the_role->save( $props );
	}

}