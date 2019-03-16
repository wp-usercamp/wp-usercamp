<?php
/**
 * Emails Controller
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Emails Class.
 */
class UC_Emails {

	/**
	 * Array of email notification classes
	 */
	public $emails = array();

	/**
	 * The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor for the email class hooks in all emails that can be sent.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init email classes.
	 */
	public function init() {

	}

}