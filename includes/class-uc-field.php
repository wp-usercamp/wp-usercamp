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
		'key',
		'type',
		'icon',
		'label',
		'edit_label',
		'view_label',
		'placeholder',
		'helper',
		'can_view',
		'is_readonly',
		'is_private',
		'is_required',
		'dropdown_options',
		'checkbox_options',
		'radio_options',
		'blocked_emails',
		'allowed_emails',
		'is_crop',
		'crop_ratio',
		'vertical_crop',
		'max_image_size',
		'max_file_size',
		'error_hooks',
		'display_hooks',
		'filter_hooks',
		'presave_hooks',
		'postsave_hooks',
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