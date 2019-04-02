<?php
/**
 * Edit account
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Form_Account class.
 */
class UC_Shortcode_Form_Account {

	/**
	 * Save.
	 */
	public static function save( $object = null ) {
		global $the_form;

		if ( $object ) {
			$the_form = $object;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		$the_form->is_request = true;

		$the_user = uc_get_user( get_current_user_id() );

		/**
		 * Validates the input.
		 */
		$the_form->validate();

		// Allow errors to be extended and custom hooks.
		do_action( 'usercamp_account_validate' );

		/**
		 * Show success message.
		 */
		if ( ! $the_form->has_errors() ) {
			$the_form->is_request = false;

			do_action( 'usercamp_pre_account_update' );
			do_action( 'usercamp_pre_account_update_' . $the_form->get_endpoint() );

			// Update. Expecting sanitized data.
			$the_user->update( $the_form->postdata );

			do_action( 'usercamp_account_update' );
			do_action( 'usercamp_account_update_' . $the_form->get_endpoint() );

			uc_add_notice( __( 'Changes saved.', 'usercamp' ), 'success' );
		}

	}

}