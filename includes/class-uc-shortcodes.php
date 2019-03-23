<?php
/**
 * Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcodes class.
 */
class UC_Shortcodes {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'form'			=> __CLASS__ . '::form',
			'account'		=> __CLASS__ . '::account',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( 'usercamp_' . $shortcode, $function );
		}
	}

	/**
	 * Shortcode Wrapper.
	 */
	public static function shortcode_wrapper(
		$function,
		$atts = array(),
		$wrapper = array(
			'class'  => 'usercamp',
			'before' => null,
			'after'  => null,
		)
	) {
		ob_start();

		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];

		return ob_get_clean();
	}

	/**
	 * Output form.
	 */
	public static function form( $atts ) {
		if ( is_admin() ) {
			return;
		}
		return self::shortcode_wrapper( array( 'UC_Shortcode_Form', 'output' ), $atts );
	}

	/**
	 * Account page shortcode.
	 */
	public static function account( $atts ) {
		if ( is_admin() ) {
			return;
		}
		return self::shortcode_wrapper( array( 'UC_Shortcode_Account', 'output' ), $atts );
	}

}