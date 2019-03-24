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
		add_action( 'template_redirect', array( __CLASS__, 'login' ) );
		add_action( 'template_redirect', array( __CLASS__, 'lostpassword' ) );
		add_action( 'template_redirect', array( __CLASS__, 'edit_account' ) );
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
	 * Login.
	 */
	public static function login() {
		if ( ! $id = self::handle( 'login' ) ) {
			return;
		}
		UC_Shortcode_Form_Login::save( uc_get_form( $id ) );
	}

	/**
	 * Lost Password.
	 */
	public static function lostpassword() {
		if ( ! $id = self::handle( 'lostpassword' ) ) {
			return;
		}
		UC_Shortcode_Form_Lostpassword::save( uc_get_form( $id ) );
	}

	/**
	 * Account - Edit
	 */
	public static function edit_account() {
		if ( ! $id = self::handle( 'edit-account' ) ) {
			return;
		}
		UC_Shortcode_Form_Edit_Account::save( uc_get_form( $id ) );
	}

}

UC_Form_Handler::init();