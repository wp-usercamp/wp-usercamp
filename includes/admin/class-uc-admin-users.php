<?php
/**
 * Users Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Admin_Users class.
 */
class UC_Admin_Users {

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Customize user row actions
		add_filter( 'user_row_actions', array( $this, 'user_row_actions' ), 10, 2 );
	}

	/**
	 * User row actions.
	 */
	public function user_row_actions( $actions, $user ) {

		$actions[ 'view' ] = '<a href="' . usercamp_get_profile_url( $user->user_login ) . '" aria-label="' . esc_html__( 'View user profile', 'usercamp' ) . '">' . __( 'View', 'usercamp' ) . '</a>';
		return $actions;
	}

}

new UC_Admin_Users();