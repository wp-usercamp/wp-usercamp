<?php
/**
 * Form data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Form_Data class.
 */
class UC_Meta_Box_Form_Data {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_form;

		$thepostid      = ( isset( $post->ID ) ) ? $post->ID : 0;
		$the_form 		= $thepostid ? new UC_Form( $thepostid ) : new UC_Form();

		wp_nonce_field( 'usercamp_save_data', 'usercamp_meta_nonce' );

		include 'views/html-form-data-panel.php';
	}

	/**
	 * Return array of tabs to show.
	 */
	private static function get_tabs() {
		$tabs = apply_filters(
			'usercamp_form_data_tabs', array(
				'general'			=> array(
					'icon'	   => 'settings',
					'label'    => __( 'General', 'usercamp' ),
					'target'   => 'general_form_data',
					'class'    => array(),
					'priority' => 10,
				),
				'customize'			=> array(
					'icon'	   => 'type',
					'label'    => __( 'Customize', 'usercamp' ),
					'target'   => 'customize_form_data',
					'class'    => array(),
					'priority' => 20,
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
		global $post, $thepostid, $the_form;

		include 'views/html-form-data-general.php';
		include 'views/html-form-data-customize.php';
	}

	/**
	 * Save meta box data.
	 */
	public static function save( $post_id, $post ) {
		global $the_form;
		$props = array();

		$the_form = new UC_Form( $post_id );

		$props['type']         			= isset( $_POST['_type'] ) ? uc_clean( wp_unslash( $_POST['_type'] ) ) : '';
		$props['icons']         		= isset( $_POST['icons'] ) ? uc_clean( wp_unslash( $_POST['icons'] ) ) : '';
		$props['endpoint']         		= isset( $_POST['endpoint'] ) ? uc_clean( wp_unslash( $_POST['endpoint'] ) ) : '';
		$props['use_ajax']				= isset( $_POST['use_ajax'] ) ? 'yes' : 'no';

		if ( $props['endpoint'] !== $the_form->endpoint ) {
			delete_option( 'usercamp_account_' . uc_sanitize_title( $the_form->endpoint ) . '_form' );
		}

		$the_form->save( $props );
	}

}