<?php
/**
 * Member list data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Memberlist_Data Class.
 */
class UC_Meta_Box_Memberlist_Data {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_memberlist;

		$thepostid      = $post->ID;
		$the_memberlist = $thepostid ? new UC_Memberlist( $thepostid ) : new UC_Memberlist();

		wp_nonce_field( 'usercamp_save_data', 'usercamp_meta_nonce' );

		include 'views/html-memberlist-data-panel.php';
	}

	/**
	 * Return array of tabs to show.
	 */
	private static function get_tabs() {
		$tabs = apply_filters(
			'usercamp_memberlist_data_tabs', array(
				'general'        => array(
					'icon'	   => 'settings',
					'label'    => __( 'General', 'usercamp' ),
					'target'   => 'general_memberlist_data',
					'class'    => array(),
					'priority' => 10,
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
		global $post, $thepostid, $the_memberlist;

		include 'views/html-memberlist-data-general.php';
	}

	/**
	 * Save meta box data.
	 */
	public static function save( $post_id, $post ) {
		global $the_memberlist;
		$props = array();

		$the_memberlist = new UC_Memberlist( $post_id );

		$props['login_required']		= ! empty( $_POST['login_required'] );
		$props['use_ajax']				= isset( $_POST['use_ajax'] ) ? 'yes' : 'no';
		$props['per_page']				= isset( $_POST['per_page'] ) ? absint( wp_unslash( $_POST['per_page'] ) ) : '';
		$props['per_row']				= isset( $_POST['per_row'] ) ? absint( wp_unslash( $_POST['per_row'] ) ) : '';

		$the_memberlist->save( $props );
	}

}