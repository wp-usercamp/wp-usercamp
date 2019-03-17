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
			'register'				=> __( 'Let&#39;s get started!', 'usercamp' ),
			'got_account'			=> __( 'Got an account?', 'usercamp' ),
		), (array) $atts );

		self::verify();

		uc_get_template( 'forms/register.php', array( 'atts' => $atts ) );
	}

	/**
	 * Verify.
	 */
	public static function verify( $object = null ) {
		global $the_form;
		if ( $object ) {
			$the_form = $object;
		}
		if ( isset( $_REQUEST['usercamp-register-nonce'] ) && wp_verify_nonce( $_REQUEST['usercamp-register-nonce'], 'usercamp-register' ) ) {
			$the_form->is_request = true;
		}
	}

}