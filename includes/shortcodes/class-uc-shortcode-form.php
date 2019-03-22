<?php
/**
 * Form Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Form class.
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

		if ( ! isset( $the_form->id ) || $the_form->id != $atts[ 'id' ] ) {
			$the_form = new UC_Form( $atts[ 'id' ] );
		}

		if ( ! array_key_exists( $the_form->type, usercamp_get_form_types() ) ) {
			return;
		}

		$classname = 'UC_Shortcode_Form_' . ucfirst( $the_form->type );

		if ( class_exists( $classname ) ) {

			call_user_func( array( $classname, 'output' ), $atts );

		}
	}

}