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
		add_action( 'template_redirect', array( __CLASS__, 'edit_password' ) );
		add_action( 'template_redirect', array( __CLASS__, 'privacy' ) );
		add_action( 'template_redirect', array( __CLASS__, 'profile_redirect' ) );
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
		UC_Shortcode_Form_Account::save( uc_get_form( $id ) );
	}

	/**
	 * Account - Edit password
	 */
	public static function edit_password() {
		if ( ! $id = self::handle( 'edit-password' ) ) {
			return;
		}
		UC_Shortcode_Form_Account::save( uc_get_form( $id ) );
	}

	/**
	 * Account - Privacy
	 */
	public static function privacy() {
		if ( ! $id = self::handle( 'privacy' ) ) {
			return;
		}
		UC_Shortcode_Form_Account::save( uc_get_form( $id ) );
	}

	/**
	 * Profile redirect.
	 */
	public static function profile_redirect() {
		global $current_user;
		if ( ! is_uc_profile_page() ) {
			return;
		}

		$username = esc_attr( get_query_var( 'uc_user' ) );

		if ( empty( $username ) ) {
			if ( ! is_user_logged_in() ) {
				exit( wp_redirect( home_url() ) );
			} else {
				exit( wp_redirect( usercamp_get_profile_url( $current_user->user_login ) ) );
			}
		} else {
			if ( ! username_exists( $username ) ) {
				exit( wp_redirect( home_url() ) );
			}
		}
	}

}

UC_Form_Handler::init();