<?php
/**
 * Form Instances.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Form_Instances class.
 */
class UC_Meta_Box_Form_Instances {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_form;

		$thepostid      = $post->ID;
		$the_form 		= $thepostid ? new UC_Form( $thepostid ) : new UC_Form();

		include 'views/html-form-instances.php';
	}

}