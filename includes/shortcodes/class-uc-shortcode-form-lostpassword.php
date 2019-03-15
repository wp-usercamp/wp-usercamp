<?php
/**
 * Lost Password
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Form_Lostpassword Class.
 */
class UC_Shortcode_Form_Lostpassword {

	/**
	 * Output the shortcode.
	 */
	public static function output( $atts ) {
		global $the_form;

		$atts = array_merge( array(

		), (array) $atts );

		uc_get_template( 'forms/lostpassword.php' );
	}

}