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

	/**
	 * Get a user data.
	 */
	public function get( $key, $scope = 'edit' ) {
		if ( isset( $this->data->{$key} ) ) {
			$output = $this->data->{$key};
		}

		if ( ! empty( $output ) ) {
			$output = apply_filters( 'usercamp_get_user_' . $key, $output, $scope, $this );
			return esc_attr( $output );
		}
	}

}