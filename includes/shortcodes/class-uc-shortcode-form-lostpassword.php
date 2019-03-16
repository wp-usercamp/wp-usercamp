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
			'top_note'				=> __( 'Please write your email in the box below and we’ll send you a link to the password reset page.', 'usercamp' ),
			'retrieve_password'		=> __( 'Retrieve Password', 'usercamp' ),
			'log_in'				=> __( 'Wait, I remember!', 'usercamp' ),
		), (array) $atts );

		if ( isset( $_REQUEST['user_email'] ) && wp_verify_nonce( $_REQUEST['usercamp-lost-password-nonce'], 'usercamp-lost-password' ) ) {
			$the_form->is_request = true;

			$email = empty( $_REQUEST['user_email'] ) ? '' : uc_clean( wp_unslash( $_REQUEST['user_email'] ) );

			if ( ! $email ) {
				$the_form->error( 'user_email' );
				uc_add_notice( __( 'Please type your email.', 'usercamp' ), 'error' );
			}

			if ( ! is_email( $email ) ) {
				$the_form->error( 'user_email' );
				uc_add_notice( __( 'Invalid email format.', 'usercamp' ), 'error' );
			}

			// Fired before the actual password reset email and notice.
			do_action( 'usercamp_pre_password_reset' );

			if ( empty( $the_form->error_fields ) ) {
				$the_form->is_request = false;
				uc_add_notice( __( 'Instructions to reset your password will be sent to you shortly. Please check your email.', 'usercamp' ), 'success' );
				self::password_reset( $email );
			}

		}

		uc_get_template( 'forms/lostpassword.php', array( 'atts' => $atts ) );
	}

	/**
	 * Start password reset process.
	 */
	public static function password_reset( $email ) {

		if ( ! email_exists( $email ) ) {
			return;
		}

	}

}