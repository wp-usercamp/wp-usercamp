<?php
/**
 * Member List shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Memberlist_Shortcode Class.
 */
class UC_Meta_Box_Memberlist_Shortcode {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_memberlist;

		$thepostid      = $post->ID;
		$the_memberlist = $thepostid ? new UC_Memberlist( $thepostid ) : new UC_Memberlist();

		wp_nonce_field( 'usercamp_save_data', 'usercamp_meta_nonce' );

		include 'views/html-memberlist-shortcode.php';
	}

	/**
	 * Save meta box data.
	 */
	public static function save( $post_id, $post ) {

	}

}