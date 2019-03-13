<?php
/**
 * Memberlist Core.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UC_Abstract_Post', false ) ) {
	include_once 'abstracts/abstract-class-uc-post.php';
}

/**
 * UC_Memberlist Class.
 */
class UC_Memberlist extends UC_Abstract_Post {

	/**
	 * Post type.
	 */
	public $post_type = 'uc_memberlist';

	/**
	 * Meta keys.
	 */
	public $internal_meta_keys = array(
		'per_page',
		'per_row',
		'login_required',
		'use_ajax',
		'roles',
		'rules',
	);

}