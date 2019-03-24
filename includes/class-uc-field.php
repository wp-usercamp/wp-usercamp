<?php
/**
 * Custom Field Core.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UC_Abstract_Post', false ) ) {
	include_once 'abstracts/abstract-class-uc-post.php';
}

/**
 * UC_Field class.
 */
class UC_Field extends UC_Abstract_Post {

	/**
	 * Post type.
	 */
	public $post_type = 'uc_field';

	/**
	 * Meta keys.
	 */
	public $internal_meta_keys = array(

	);

	/**
	 * Check if field key exists.
	 */
	public function exists( $key ) {
		if ( ! is_array( $fields = get_option( 'usercamp_fields' ) ) ) {
			return false;
		}
		return array_key_exists( $key, $fields );
	}

	/**
	 * Custom save action.
	 */
	public function _save( $props ) {
		if ( $props['key'] == '' ) {
			return;
		}

		$fields = get_option( 'usercamp_fields' );
		foreach( $props as $key => $value ) {
			$fields[$props['key']][$key] = $value;
		}

		update_option( 'usercamp_fields', $fields );
	}

	/**
	 * When this item is deleted.
	 */
	public function _delete() {
		$fields = get_option( 'usercamp_fields' );
		if ( is_array( $fields ) && array_key_exists( $this->key, $fields ) ) {
			unset( $fields[ $this->key ] );
			update_option( 'usercamp_fields', $fields );
		}
	}

}