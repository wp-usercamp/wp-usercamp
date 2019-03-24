<?php
/**
 * Edit account
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Shortcode_Form_Edit_Account class.
 */
class UC_Shortcode_Form_Edit_Account {

	/**
	 * Save.
	 */
	public static function save( $object = null ) {
		global $the_form;

		if ( $object ) {
			$the_form = $object;
		}

	}

}