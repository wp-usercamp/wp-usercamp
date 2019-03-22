<?php
/**
 * User class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_User class.
 */
class UC_User {

	/**
	 * User ID
	 */
	public $user_id = 0;

	/**
	 * Construct.
	 */
	public function __construct( $user_id ) {
		$this->init( $user_id );
	}

	/**
	 * Init.
	 */
	public function init( $user_id ) {
		$user = get_user_by( 'id', $user_id );

		if ( ! isset( $user->ID ) ) {
			return;
		}
		$this->user_id = $user->ID;

		foreach( $user as $key => $value ) {
			$this->{$key} = $value;
		}
	}

}