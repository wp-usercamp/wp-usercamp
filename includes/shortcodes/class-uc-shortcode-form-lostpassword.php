<?php
/**
 * Lost Password
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Form_Lostpassword class.
 */
class UC_Shortcode_Form_Lostpassword {

	/**
	 * Output the shortcode.
	 */
	public static function output( $atts ) {
		global $the_form;

		$atts = array_merge( array(
			'top_note'				=> __( 'Please write your email in the box below and we’ll send you a link to the password reset page.', 'usercamp' ),
			'first_button'			=> __( 'Retrieve Password', 'usercamp' ),
			'second_button'			=> __( 'Wait, I remember!', 'usercamp' ),
		), (array) $atts );

		uc_get_template( 'form/form.php', array( 'atts' => $atts ) );
	}

	/**
	 * Save.
	 */
	public static function save( $object = null ) {
		global $the_form;

		if ( $object ) {
			$the_form = $object;
		}

		if ( ! isset( $_REQUEST['user_email'] ) ) {
			return;
		}

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

		/**
		 * Validates the input.
		 */
		$the_form->validate();

		// Allow errors to be extended and custom hooks.
		do_action( 'usercamp_password_reset_validate' );

		/**
		 * Show success message.
		 */
		if ( ! $the_form->has_errors() ) {
			$the_form->is_request = false;

			do_action( 'usercamp_pre_password_reset' );

			self::password_reset( $email );

			do_action( 'usercamp_password_reset' );

			uc_add_notice( __( 'Instructions to reset your password will be sent to you shortly. Please check your email.', 'usercamp' ), 'success' );
		}

	}

	/**
	 * Start password reset process.
	 */
	public static function password_reset( $email ) {
		global $the_form;

		if ( ! email_exists( $email ) ) {
			return;
		}

		// Send email notification.
		uc()->mailer();
		do_action( 'usercamp_reset_password_notification', $email );

	}

}