<?php
/**
 * Form Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Form Class.
 */
class UC_Shortcode_Form {

	/**
	 * Get the shortcode content.
	 */
	public static function get( $atts ) {
		return UC_Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 */
	public static function output( $atts ) {
		global $the_form;

		$the_form = new UC_Form( $atts['id'] );

		// Check that form is there.
		if ( ! array_key_exists( $the_form->type, usercamp_get_form_types() ) ) {
			return;
		}

		// Load proper class.
		$classname = 'UC_Shortcode_Form_' . ucfirst( $the_form->type );
		if ( class_exists( $classname ) ) {
			$classname::output( $atts );
		}
	}

}