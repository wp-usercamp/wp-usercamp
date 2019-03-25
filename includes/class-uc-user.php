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
	 * Meta keys that should not be sent through metadata.
	 */
	private $core_meta_keys = array(
		'user_login',
		'user_email',
		'user_pass',
	);

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
		$output = null;

		if ( isset( $this->data->{$key} ) ) {
			$output = $this->data->{$key};
		} elseif ( isset( $this->{$key} ) ) {
			$output = $this->{$key};
		} else {
			$output = get_user_meta( $this->user_id, $key, true );
		}

		return apply_filters( 'usercamp_get_user_' . $key, $output, $scope, $this );
	}

	/**
	 * Update user details.
	 */
	public function update( $metadata ) {

		foreach( $metadata as $key => $value ) {
			if ( ! in_array( $key, $this->core_meta_keys ) ) {
				update_user_meta( $this->user_id, $key, $value );
			}
		}
	}

}