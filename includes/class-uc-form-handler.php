<?php
/**
 * Form Handler.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Form_Handler class.
 */
class UC_Form_Handler {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'user_login' ) );
		add_action( 'template_redirect', array( __CLASS__, 'user_password_reset' ) );
	}

	/**
	 * Get valid handler.
	 */
	public static function handle( $mode ) {
		if ( ! isset( $_REQUEST['usercamp-' . $mode . '-nonce'] ) 
			|| ! wp_verify_nonce( $_REQUEST['usercamp-' . $mode. '-nonce'], 'usercamp-' . $mode )
		) {
			return false;
		}
		if ( ! empty( $_REQUEST[ '_' . $mode . '_id' ] ) ) {
			return absint( $_REQUEST[ '_' . $mode . '_id' ] );
		}
		return false;
	}

	/**
	 * User login.
	 */
	public static function user_login() {
		if ( ! $id = self::handle( 'login' ) ) {
			return;
		}

		UC_Shortcode_Form_Login::verify( uc_get_form( $id ) );
	}

	/**
	 * User password reset.
	 */
	public static function user_password_reset() {
		if ( ! $id = self::handle( 'lostpassword' ) ) {
			return;
		}

		UC_Shortcode_Form_Lostpassword::verify( uc_get_form( $id ) );
	}

}

UC_Form_Handler::init();