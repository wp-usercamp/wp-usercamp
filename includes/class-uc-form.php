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

}