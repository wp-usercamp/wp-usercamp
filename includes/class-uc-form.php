<?php
/**
 * Forms Core.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UC_Abstract_Post', false ) ) {
	include_once 'abstracts/abstract-class-uc-post.php';
}

/**
 * UC_Form Class.
 */
class UC_Form extends UC_Abstract_Post {

	/**
	 * Post type.
	 */
	public $post_type = 'uc_form';

	/**
	 * Meta keys.
	 */
	public $internal_meta_keys = array(
		'type',
		'fields',
		'row_count',
		'cols',
	);

	/**
	 * Does the form have fields?
	 */
	public function has_fields() {
		if ( ! empty( $this->fields ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get fields per specific row.
	 */
	public function fields_in( $row = 0, $col = 0 ) {
		if ( empty( $this->fields ) ) {
			return;
		}

		// Fix column start at zero.
		$col = $col + 1;

		return array_filter( $this->fields, function( $val ) use ($row, $col) {
			return ( $val['row'] == absint( $row ) && $val['col'] == $col );
		} );
	}

	/**
	 * Check if form has errors.
	 */
	public function got_errors() {
		return ! empty( $this->error_fields );
	}

	/**
	 * Sets fields to have error.
	 */
	public function error( $field ) {
		if ( ! in_array( $field, $this->error_fields ) ) {
			$this->error_fields[] = $field;
		}
	}

	/**
	 * Check if key is an error.
	 */
	public function has_error( $key ) {
		return in_array( $key, $this->error_fields );
	}

	/**
	 * Get error fields.
	 */
	public function get_error_fields() {
		$error_fields = null;
		if ( ! empty( $this->error_fields ) ) {
			foreach( $this->error_fields as $field ) {
				$error_fields[] = '.' . $field . '_field';
			}
		}
		return $error_fields;
	}

	/**
	 * Validation of user input.
	 */
	public function validate() {
		if ( empty( $this->fields ) ) {
			return;
		}
		foreach( $this->fields as $key => $array ) {
			$data = $array['data'];
			$validate = 'validate_' . $data['type'];
			if ( method_exists( $this, $validate ) ) {
				$this->{$validate}( $data );
			} else {
				$this->validate_input( $data );
			}
		}
	}

	/**
	 * Default validation.
	 */
	public function validate_input( $data ) {
		extract( $data );

		$value = ! isset( $_REQUEST[ $key ] ) ? '' : uc_clean( wp_unslash( $_REQUEST[ $key ] ) );

		// Check if required.
		if ( ! $value && (bool) $is_required == 1 ) {
			$this->error( $key );
			uc_add_notice( sprintf( __( '%s is required.', 'usercamp' ), $label ), 'error' );
		}

	}

}