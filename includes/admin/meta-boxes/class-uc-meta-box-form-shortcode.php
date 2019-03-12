<?php
/**
 * Form shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Form_Shortcode Class.
 */
class UC_Meta_Box_Form_Shortcode {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_form;

		$thepostid      = $post->ID;
		$the_form 		= $thepostid ? new UC_Form( $thepostid ) : new UC_Form();

		wp_nonce_field( 'usercamp_save_data', 'usercamp_meta_nonce' );

		include 'views/html-form-shortcode.php';
	}

	/**
	 * Save meta box data.
	 */
	public static function save( $post_id, $post ) {

	}

}