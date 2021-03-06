<?php
/**
 * Form shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Form_Shortcode class.
 */
class UC_Meta_Box_Form_Shortcode {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_form;

		$thepostid      = $post->ID;
		$the_form 		= $thepostid ? new UC_Form( $thepostid ) : new UC_Form();

		include 'views/html-form-shortcode.php';
	}

}