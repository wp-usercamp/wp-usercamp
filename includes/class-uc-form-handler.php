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
	 * User login.
	 */
	public static function user_login() {
		if ( ! isset( $_REQUEST['usercamp-login-nonce'] ) || ! wp_verify_nonce( $_REQUEST['usercamp-login-nonce'], 'usercamp-login' ) ) {
			return;
		}

		UC_Shortcode_Form_Login::verify( new UC_Form( absint( $_REQUEST[ '_login_id' ] ) ) );
	}

	/**
	 * User password reset.
	 */
	public static function user_password_reset() {
		if ( ! isset( $_REQUEST['usercamp-lostpassword-nonce'] ) || ! wp_verify_nonce( $_REQUEST['usercamp-lostpassword-nonce'], 'usercamp-lostpassword' ) ) {
			return;
		}

		UC_Shortcode_Form_Lostpassword::verify( new UC_Form( absint( $_REQUEST[ '_lostpassword_id' ] ) ) );
	}

}

UC_Form_Handler::init();