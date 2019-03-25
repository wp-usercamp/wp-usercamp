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

		/**
		 * Set current user.
		 */
		$the_user = uc_get_user( get_current_user_id() );

		/**
		 * Validates the input.
		 */
		$the_form->validate();

		/**
		 * Show success message.
		 */
		if ( ! $the_form->has_errors() ) {
			$the_form->is_request = false;

			// Update. Expecting sanitized data.
			$the_user->update( $the_form->postdata );

			uc_add_notice( __( 'Changes saved.', 'usercamp' ), 'success' );
		}
	}

}