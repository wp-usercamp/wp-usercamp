<?php
/**
 * My Account Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_My_Account class.
 */
class UC_Shortcode_My_Account {

	/**
	 * Get the shortcode content.
	 */
	public static function get( $atts ) {
		return UC_Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 */
	public static function output( $atts ) {

		if ( ! is_user_logged_in() ) {

		} else {
			// Start output buffer since the html may need discarding for BW compatibility.
			ob_start();

			// Collect notices before output.
			$notices = uc_get_notices();

			// Output the new account page.
			self::my_account( $atts );

			// Send output buffer.
			ob_end_flush();
		}
	}

	/**
	 * My account page.
	 */
	private static function my_account( $atts ) {
		$atts = array_merge( array(

		), (array) $atts );

		uc_get_template(
			'myaccount/my-account.php', array(
				'current_user' => get_user_by( 'id', get_current_user_id() ),
			)
		);
	}

}