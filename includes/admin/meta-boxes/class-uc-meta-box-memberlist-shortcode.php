<?php
/**
 * Member List shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Memberlist_Shortcode class.
 */
class UC_Meta_Box_Memberlist_Shortcode {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_memberlist;

		$thepostid      = $post->ID;
		$the_memberlist = $thepostid ? new UC_Memberlist( $thepostid ) : new UC_Memberlist();

		include 'views/html-memberlist-shortcode.php';
	}

}