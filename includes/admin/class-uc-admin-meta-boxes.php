<?php
/**
 * Meta Boxes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Admin_Meta_Boxes Class.
 */
class UC_Admin_Meta_Boxes {

	/**
	 * Is meta boxes saved once?
	 */
	private static $saved_meta_boxes = false;

	/**
	 * Meta box error messages.
	 */
	public static $meta_box_errors = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );

		// Save Custom Field Meta Boxes.
		add_action( 'uc_field_process_metadata', 'UC_Meta_Box_Field_Data::save', 10, 2 );

		// Save User Role Meta Boxes.
		add_action( 'uc_role_process_metadata', 'UC_Meta_Box_Role_Data::save', 10, 2 );

		// Save Member List Meta Boxes.
		add_action( 'uc_memberlist_process_metadata', 'UC_Meta_Box_Memberlist_Data::save', 10, 2 );

		// Error handling (for showing errors from meta boxes on next page load).
		add_action( 'admin_notices', array( $this, 'output_errors' ) );
		add_action( 'shutdown', array( $this, 'save_errors' ) );
	}

	/**
	 * Remove bloat.
	 */
	public function remove_meta_boxes() {
		foreach( uc_get_post_types() as $post_type ) {
			remove_meta_box( 'slugdiv', $post_type, 'normal' );
		}
	}

	/**
	 * Add WC Meta boxes.
	 */
	public function add_meta_boxes() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		// Forms.
		add_meta_box( 'usercamp-form-builder', __( 'Form Builder', 'usercamp' ), 'UC_Meta_Box_Form_Builder::output', 'uc_form', 'normal', 'high' );
		add_meta_box( 'usercamp-form-shortcode', __( 'Shortcode', 'usercamp' ), 'UC_Meta_Box_Form_Shortcode::output', 'uc_form', 'side', 'default' );

		// Custom fields.
		add_meta_box( 'usercamp-field-data', __( 'Custom Field Settings', 'usercamp' ), 'UC_Meta_Box_Field_Data::output', 'uc_field', 'normal', 'high' );

		// User roles.
		add_meta_box( 'usercamp-role-data', __( 'Edit Role Permissions', 'usercamp' ), 'UC_Meta_Box_Role_Data::output', 'uc_role', 'normal', 'high' );

		// Member Lists.
		add_meta_box( 'usercamp-memberlist-data', __( 'Member List Settings', 'usercamp' ), 'UC_Meta_Box_Memberlist_Data::output', 'uc_memberlist', 'normal', 'high' );
		add_meta_box( 'usercamp-memberlist-shortcode', __( 'Shortcode', 'usercamp' ), 'UC_Meta_Box_Memberlist_Shortcode::output', 'uc_memberlist', 'side', 'default' );
	}

	/**
	 * Check if we're saving, the trigger an action based on the post type.
	 */
	public function save_meta_boxes( $post_id, $post ) {
		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) || self::$saved_meta_boxes ) {
			return;
		}

		// Dont' save meta boxes for revisions or autosaves
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the nonce
		if ( empty( $_POST['usercamp_meta_nonce'] ) || ! wp_verify_nonce( $_POST['usercamp_meta_nonce'], 'usercamp_save_data' ) ) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}

		// Check user has permission to edit
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// We need this save event to run once to avoid potential endless loops. This would have been perfect:
		self::$saved_meta_boxes = true;

		// Check the post type
		if ( in_array( $post->post_type, uc_get_post_types() ) ) {
			do_action( $post->post_type . '_process_metadata', $post_id, $post );
		}
	}

	/**
	 * Show any stored error messages.
	 */
	public function output_errors() {
		$errors = array_filter( (array) get_option( 'usercamp_meta_box_errors' ) );

		if ( ! empty( $errors ) ) {

			echo '<div id="usercamp_errors" class="error notice is-dismissible">';

			foreach ( $errors as $error ) {
				echo '<p>' . wp_kses_post( $error ) . '</p>';
			}

			echo '</div>';

			// Clear
			delete_option( 'usercamp_meta_box_errors' );
		}
	}

	/**
	 * Add an error message.
	 */
	public static function add_error( $text ) {
		self::$meta_box_errors[] = $text;
	}

	/**
	 * Save errors to an option.
	 */
	public function save_errors() {
		update_option( 'usercamp_meta_box_errors', self::$meta_box_errors );
	}

}

new UC_Admin_Meta_Boxes();