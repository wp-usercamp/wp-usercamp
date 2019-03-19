<?php
/**
 * Register
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Form_Register Class.
 */
class UC_Shortcode_Form_Register {

	/**
	 * Output the shortcode.
	 */
	public static function output( $atts ) {
		global $the_form;

		$atts = array_merge( array(
			'top_note'				=> '',
			'first_button'			=> __( 'Let&#39;s get started!', 'usercamp' ),
			'second_button'			=> __( 'Got an account?', 'usercamp' ),
		), (array) $atts );

		uc_get_template( 'forms/form.php', array( 'atts' => $atts ) );
	}

	/**
	 * Verify.
	 */
	public static function verify( $object = null ) {
		global $the_form;

		if ( $object ) {
			$the_form = $object;
		}

	}

}