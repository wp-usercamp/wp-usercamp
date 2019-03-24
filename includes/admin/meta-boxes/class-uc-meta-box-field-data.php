<?php
/**
 * Custom field data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Field_Data class.
 */
class UC_Meta_Box_Field_Data {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_field;

		$thepostid      = ( isset( $post->ID ) ) ? $post->ID : 0;
		$the_field 		= $thepostid ? new UC_Field( $thepostid ) : new UC_Field();

		wp_nonce_field( 'usercamp_save_data', 'usercamp_meta_nonce' );

		include 'views/html-field-data-panel.php';
	}

	/**
	 * Return array of tabs to show.
	 */
	private static function get_tabs() {
		$tabs = apply_filters(
			'usercamp_field_data_tabs', array(
				'general'        => array(
					'icon'	   => 'settings',
					'label'    => __( 'General', 'usercamp' ),
					'target'   => 'general_field_data',
					'class'    => array(),
					'priority' => 10,
				),
				'properties'	=> array(
					'icon'	   => 'database',
					'label'    => __( 'Properties', 'usercamp' ),
					'target'   => 'properties_field_data',
					'class'    => array(),
					'priority' => 20,
				),
				'customize'		=> array(
					'icon'	   => 'type',
					'label'    => __( 'Customize', 'usercamp' ),
					'target'   => 'customize_field_data',
					'class'    => array(),
					'priority' => 30,
				),
				'advanced'		=> array(
					'icon'	   => 'code',
					'label'    => __( 'Advanced', 'usercamp' ),
					'target'   => 'advanced_field_data',
					'class'    => array(),
					'priority' => 40,
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
		global $post, $thepostid, $the_field;

		include 'views/html-field-data-general.php';
		include 'views/html-field-data-properties.php';
		include 'views/html-field-data-customize.php';
		include 'views/html-field-data-advanced.php';
	}

	/**
	 * Save meta box data.
	 */
	public static function save( $post_id, $post ) {
		global $the_field;
		$props = array();

		$the_field = new UC_Field( $post_id );

		$props['key']         			= isset( $_POST['key'] ) ? sanitize_title( wp_unslash( $_POST['key'] ) ) : '';
		$props['type']         			= isset( $_POST['type'] ) ? uc_clean( wp_unslash( $_POST['type'] ) ) : '';
		$props['can_view']				= isset( $_POST['can_view'] ) ? uc_clean( wp_unslash( $_POST['can_view'] ) ) : '';
		$props['is_private']			= ! empty( $_POST['is_private'] );
		$props['is_readonly']			= ! empty( $_POST['is_readonly'] );
		$props['is_required']			= ! empty( $_POST['is_required'] );
		$props['is_crop']				= ! empty( $_POST['is_crop'] );
		$props['vertical_crop']			= ! empty( $_POST['vertical_crop'] );
		$props['label']					= uc_clean( wp_unslash( $_POST['post_title'] ) );
		$props['edit_label']			= uc_clean( wp_unslash( $_POST['edit_label'] ) );
		$props['view_label']			= uc_clean( wp_unslash( $_POST['view_label'] ) );
		$props['placeholder']			= uc_clean( wp_unslash( $_POST['placeholder'] ) );
		$props['icon']					= uc_clean( wp_unslash( $_POST['icon'] ) );
		$props['crop_ratio']			= uc_clean( wp_unslash( $_POST['crop_ratio'] ) );
		$props['helper']				= wp_kses_post( wp_unslash( $_POST['helper'] ) );
		$props['dropdown_options']		= wp_kses_post( wp_unslash( $_POST['dropdown_options'] ) );
		$props['checkbox_options']		= wp_kses_post( wp_unslash( $_POST['checkbox_options'] ) );
		$props['radio_options']			= wp_kses_post( wp_unslash( $_POST['radio_options'] ) );
		$props['blocked_emails']		= wp_kses_post( wp_unslash( $_POST['blocked_emails'] ) );
		$props['allowed_emails']		= wp_kses_post( wp_unslash( $_POST['allowed_emails'] ) );
		$props['error_hooks']			= wp_kses_post( wp_unslash( $_POST['error_hooks'] ) );
		$props['error_hooks']			= wp_kses_post( wp_unslash( $_POST['display_hooks'] ) );
		$props['display_hooks']			= wp_kses_post( wp_unslash( $_POST['filter_hooks'] ) );
		$props['presave_hooks']			= wp_kses_post( wp_unslash( $_POST['presave_hooks'] ) );
		$props['postsave_hooks']		= wp_kses_post( wp_unslash( $_POST['postsave_hooks'] ) );
		$props['max_image_size']		= ! empty( $_POST['max_image_size'] ) ? absint( wp_unslash( $_POST['max_image_size'] ) ) : '';
		$props['max_file_size']			= ! empty( $_POST['max_file_size'] ) ? absint( wp_unslash( $_POST['max_file_size'] ) ) : '';

		// No key was provided.
		if ( ! $props['key'] ) {
			UC_Admin_Meta_Boxes::add_error( __( 'Please enter a unique meta key to use for this custom field below.', 'usercamp' ) );
		}

		// Check for key change.
		if ( $props['key'] != $the_field->key ) {
			if ( $the_field->exists( $props['key'] ) ) {
				UC_Admin_Meta_Boxes::add_error( __( 'The custom key was not changed because It is already used by another custom field.', 'usercamp' ) );
				$props['key'] = $the_field->key;
			} else {
				$the_field->_delete();
			}
		}

		$the_field->save( $props );
	}

}