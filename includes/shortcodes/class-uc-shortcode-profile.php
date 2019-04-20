<?php
/**
 * Profile Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Profile class.
 */
class UC_Shortcode_Profile {

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
		ob_start();

		self::profile( $atts );

		// Send output buffer.
		ob_end_flush();
	}

	/**
	 * Profile page.
	 */
	private static function profile( $atts ) {
		$atts = array_merge( array(

		), (array) $atts );

		uc_get_template(
			'profile/profile.php', array(
				'the_user' 		=> uc_get_user( uc_get_active_profile_id() ),
			)
		);
	}

}