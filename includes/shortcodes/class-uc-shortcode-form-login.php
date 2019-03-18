<?php
/**
 * Login
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Form_Login Class.
 */
class UC_Shortcode_Form_Login {

	/**
	 * Output the shortcode.
	 */
	public static function output( $atts ) {
		global $the_form;

		$atts = array_merge( array(
			'top_note'				=> '',
			'log_in'				=> __( 'Log In', 'usercamp' ),
			'create_account'		=> __( 'Create Account?', 'usercamp' ),
		), (array) $atts );

		self::verify();

		uc_get_template( 'forms/login.php', array( 'atts' => $atts ) );
	}

	/**
	 * Verify.
	 */
	public static function verify( $object = null ) {
		global $the_form;
		if ( $object ) {
			$the_form = $object;
		}
		if ( isset( $_REQUEST['user_pass'] ) && wp_verify_nonce( $_REQUEST['usercamp-login-nonce'], 'usercamp-login' ) ) {
			$the_form->is_request = true;

			$user_login		= isset( $_REQUEST[ 'user_login'] ) ? 1 : 0;
			$user_email		= isset( $_REQUEST[ 'user_email'] ) ? 1 : 0;
			$user_pass 		= empty( $_REQUEST['user_pass'] ) ? '' : $_REQUEST['user_pass']; 

			// Verify user input.
			if ( ! $user_login && ! $user_email ) {
				return;
			}
			if ( ! $user_pass ) {
				$the_form->error( 'user_pass' );
			}
			if ( $user_login && ! sanitize_user( $_REQUEST[ 'user_login' ] ) ) {
				$the_form->error( 'user_login' );
			}
			if ( $user_email && ! sanitize_user( $_REQUEST[ 'user_email' ] ) ) {
				$the_form->error( 'user_email' );
			}

			if ( $the_form->got_errors() ) {
				uc_add_notice( __( 'You provided missing details for authentication.', 'usercamp' ), 'error' );
			}

			// Get user_login.
			if ( ! $user_login && $user_email ) {
				if ( ! is_email( sanitize_email( $_REQUEST[ 'user_email' ] ) ) ) {
					$user_login = sanitize_user( $_REQUEST[ 'user_email' ] );
				} else {
					$user = get_user_by( 'email', sanitize_email( $_REQUEST[ 'user_email' ] ) );
					if ( isset( $user->user_login ) ) {
						$user_login = $user->user_login;
					}
				}
			} else if ( $user_login ) {
				$user_login = sanitize_user( $_REQUEST[ 'user_login' ] );
			}

			// Get the user object.
			if ( ! isset( $user ) ) {
				$user = get_user_by( 'login', $user_login );
			}

			// Check password.
			if ( ! isset( $user->user_login ) || ! $auth = wp_check_password( $user_pass, $user->user_pass, $user->ID ) ) {

				uc_add_notice( __( 'Username or password is not correct.', 'usercamp' ), 'error' );

			} else {

				wp_set_current_user( $user->ID );
				wp_set_auth_cookie( $user->ID, false );

				do_action( 'wp_login', $user->user_login, $user );

				$redirect_to = user_admin_url();
				exit( wp_redirect( $redirect_to ) );

			}

		}
	}

}