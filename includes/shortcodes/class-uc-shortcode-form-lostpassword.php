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

		if ( isset( $_REQUEST['user_email'] ) && wp_verify_nonce( $_REQUEST['usercamp-lost-password-nonce'], 'usercamp-lost-password' ) ) {

			$email = empty( $_REQUEST['user_email'] ) ? '' : uc_clean( wp_unslash( $_REQUEST['user_email'] ) );

			if ( ! $email ) {
				uc_add_notice( __( 'Please type your email.', 'usercamp' ), 'error' );
			}

			if ( ! is_email( $email ) ) {
				uc_add_notice( __( 'Invalid email format.', 'usercamp' ), 'error' );
			}

			if ( uc_notice_count( 'error' ) == 0 ) {
				uc_add_notice( __( 'Instructions to reset your password will be sent to you shortly. Please check your email.', 'usercamp' ), 'success' );
				self::password_reset( $email );
			}
		}

		uc_get_template( 'forms/lostpassword.php' );
	}

	/**
	 * Start password reset process.
	 */
	public static function password_reset( $email ) {

	}

}