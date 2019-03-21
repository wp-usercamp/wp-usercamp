<?php
/**
 * Form builder.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Meta_Box_Form_Builder class.
 */
class UC_Meta_Box_Form_Builder {

	/**
	 * Output the metabox.
	 */
	public static function output( $post ) {
		global $thepostid, $the_form;

		$thepostid      = $post->ID;
		$the_form 		= $thepostid ? new UC_Form( $thepostid ) : new UC_Form();

		include 'views/html-form-builder.php';

		// modals
		include 'views/modals/html-add-element.php';
		include 'views/modals/html-add-field.php';
	}

}