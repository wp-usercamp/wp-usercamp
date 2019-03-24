<?php
/**
 * Login
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Form_Login class.
 */
class UC_Shortcode_Form_Login {

	/**
	 * Output the shortcode.
	 */
	public static function output( $atts ) {
		global $the_form;

		$atts = array_merge( array(
			'top_note'				=> '',
			'first_button'			=> __( 'Log In', 'usercamp' ),
			'second_button'			=> __( 'Create Account?', 'usercamp' ),
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

		$the_form->is_request = true;

		$user			= '';
		$username		= '';
		$user_field		= 'user_login';
		$user_login		= isset( $_REQUEST[ 'user_login' ] ) ? 1 : 0;
		$user_email		= isset( $_REQUEST[ 'user_email' ] ) ? 1 : 0;
		$user_pass 		= isset( $_REQUEST[ 'user_pass' ]  ) ? 1 : 0;

		if ( ! $user_pass ) {
			return;
		} else {
			$password = $_REQUEST[ 'user_pass' ];
		}

		if ( $user_login ) {
			$username = $_REQUEST[ 'user_login' ];
		} else if ( $user_email ) {
			$username = $_REQUEST[ 'user_email' ];
			$user_field = 'email';
		} else {
			return;
		}

		if ( ! $username ) {
			if ( $user_field == 'email' ) {
				$the_form->error( 'user_email' );
				uc_add_notice( __( 'Please enter your email.', 'usercamp' ), 'error' );
			} else {
				$the_form->error( 'user_login' );
				uc_add_notice( __( 'Please enter your username.', 'usercamp' ), 'error' );
			}
		} else {
			if ( $user_field == 'email' && ! is_email( $username ) ) {
				$the_form->error( 'user_email' );
				uc_add_notice( __( 'Invalid email format.', 'usercamp' ), 'error' );
			} else if ( $user_field == 'email' && is_email( $username ) ) {
				$user = get_user_by( 'email', $username );
			}
		}

		if ( ! $password ) {
			$the_form->error( 'user_pass' );
			uc_add_notice( __( 'Please enter your password.', 'usercamp' ), 'error' );
		}

		if ( $the_form->has_errors() ) {
			return;
		}

		if ( ! isset( $user->user_login ) ) {
			$user = get_user_by( 'login', $username );
		}

		// Check credentials.
		if ( ! isset( $user->user_login ) || ! $auth = wp_check_password( $password, $user->user_pass, $user->ID ) ) {

			$the_form->error( 'global' );
			uc_add_notice( __( 'Username or password is not correct.', 'usercamp' ), 'error' );

		} else {

			if ( is_user_logged_in() ) {
				wp_logout();
			}

			wp_set_current_user( $user->ID, $user->user_login );
			wp_set_auth_cookie( $user->ID );

			do_action( 'wp_login', $user->user_login, $user );
			do_action( 'usercamp_login_success', $user->ID, $user );

			if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$the_form->js_redirect = admin_url();
			} else {
				exit( wp_safe_redirect( admin_url() ) );
			}

		}

	}

}