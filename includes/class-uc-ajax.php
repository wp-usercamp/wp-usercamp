<?php
/**
 * AJAX Events.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_AJAX class.
 */
class UC_AJAX {

	/**
	 * Hook in ajax handlers.
	 */
	public static function init() {
		self::add_ajax_events();
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public static function add_ajax_events() {
		// usercamp_EVENT => nopriv.
		$ajax_events = array(
			'send_form'					=> true,
			'save_form'					=> false,
			'add_field'					=> false,
			'create_forms'				=> false,
			'create_fields'				=> false,
			'create_roles'				=> false,
			'create_memberlists'		=> false,
			'duplicate_form'			=> false,
			'duplicate_field'			=> false,
			'duplicate_role'			=> false,
			'duplicate_memberlist'		=> false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_usercamp_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_usercamp_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
	}

	/**
	 * Send a form.
	 */
	public static function send_form() {

		$the_form = new UC_Form( absint( $_REQUEST['id'] ) );

		switch( $the_form->type ) :

			case 'lostpassword' :
				UC_Shortcode_Form_Lostpassword::verify( $the_form );
				break;

		endswitch;

		$html = uc_print_notices( true );

		wp_send_json( array( 'html' => $html ) );
	}

	/**
	 * Save a form.
	 */
	public static function save_form() {

		check_ajax_referer( 'usercamp-save-form', 'security' );

		if ( ! current_user_can( 'edit_uc_forms' ) ) {
			wp_die( -1 );
		}

		$the_form = new UC_Form( absint( $_POST['id'] ) );
		$the_form->save( array(
			'fields'	=> isset( $_POST['fields'] ) ? uc_clean( $_POST[ 'fields'] ) : '',
			'row_count'	=> absint( $_POST['row_count'] ),
			'cols'		=> uc_clean( $_POST['cols'] ),
		) );

		wp_die();
	}

	/**
	 * Add a field.
	 */
	public static function add_field() {

		check_ajax_referer( 'usercamp-add-field', 'security' );

		if ( ! current_user_can( 'edit_uc_fields' ) ) {
			wp_die( -1 );
		}

		$errors = array();
		$props = array();

		$field = new UC_Field();

		$props['label'] 				= isset( $_POST['label'] ) ? uc_clean( wp_unslash( $_POST['label'] ) ) : '';
		$props['key']         			= isset( $_POST['key'] ) ? uc_sanitize_endpoint_slug( wp_unslash( $_POST['key'] ) ) : '';

		if ( ! $props['label'] ) {
			$errors['label'] = __( 'You must provide a title for this custom field.', 'usercamp' );
		}

		if ( ! $props['key'] ) {
			$errors['key'] = __( 'You must provide a unique key for this custom field.', 'usercamp' );
		}

		if ( $field->exists( $props['key'] ) ) {
			$errors['key'] = __( 'The key provided is already in use. Please write a unique key.', 'usercamp' );
		}

		// Send errors back to form.
		if ( ! empty( $errors ) ) {
			wp_send_json( array( 'errors' => $errors ) );
		}

		// No errors? Setup all props now.
		$props['type']         			= isset( $_POST['type'] ) ? uc_clean( wp_unslash( $_POST['type'] ) ) : '';
		$props['can_view']				= isset( $_POST['can_view'] ) ? uc_clean( wp_unslash( $_POST['can_view'] ) ) : '';
		$props['icon']					= isset( $_POST['icon'] ) ? uc_clean( wp_unslash( $_POST['icon'] ) ) : '';
		$props['is_private']			= ! empty( $_POST['is_private'] );
		$props['is_readonly']			= ! empty( $_POST['is_readonly'] );
		$props['is_required']			= ! empty( $_POST['is_required'] );
		$props['is_crop']				= ! empty( $_POST['is_crop'] );
		$props['vertical_crop']			= ! empty( $_POST['vertical_crop'] );
		$props['edit_label']			= uc_clean( wp_unslash( $_POST['edit_label'] ) );
		$props['view_label']			= uc_clean( wp_unslash( $_POST['view_label'] ) );
		$props['crop_ratio']			= uc_clean( wp_unslash( $_POST['crop_ratio'] ) );
		$props['dropdown_options']		= wp_kses_post( wp_unslash( $_POST['dropdown_options'] ) );
		$props['checkbox_options']		= wp_kses_post( wp_unslash( $_POST['checkbox_options'] ) );
		$props['radio_options']			= wp_kses_post( wp_unslash( $_POST['radio_options'] ) );
		$props['blocked_emails']		= wp_kses_post( wp_unslash( $_POST['blocked_emails'] ) );
		$props['allowed_emails']		= wp_kses_post( wp_unslash( $_POST['allowed_emails'] ) );
		$props['error_hooks']			= wp_kses_post( wp_unslash( $_POST['error_hooks'] ) );
		$props['error_hooks']			= wp_kses_post( wp_unslash( $_POST['display_hooks'] ) );
		$props['display_hooks']			= wp_kses_post( wp_unslash( $_POST['filter_hooks'] ) );
		$props['presave_hooks']			= wp_kses_post( wp_unslash( $_POST['presave_hooks'] ) );
		$props['postsave_hooks']		= wp_kses_post( wp_unslash( $_POST['postsave_hooks'] ) );
		$props['max_image_size']		= isset( $_POST['max_image_size'] ) ? absint( wp_unslash( $_POST['max_image_size'] ) ) : '';
		$props['max_file_size']			= isset( $_POST['max_file_size'] ) ? absint( wp_unslash( $_POST['max_file_size'] ) ) : '';

		// Add the field to database.
		if ( empty( $errors ) ) {
			$field->set( 'post_title', $props['label'] );
			$field->set( 'post_name', uc_clean( wp_unslash( $props['key'] ) ) );
			$field->set( 'meta_input', $props );
			$field->insert();
			$field->save( $field->meta_input );
		}

		$html = '<a href="#" class="button button-secondary insert_field" ' . uc_get_data_attributes( $props ) . '>' . esc_html__( $props['label'] ) . '</a>';

		wp_send_json( array( 'data' => $html ) );
	}

	/**
	 * Create default forms.
	 */
	public static function create_forms() {

		check_ajax_referer( 'usercamp-create-forms', 'security' );

		if ( ! current_user_can( 'publish_uc_forms' ) ) {
			wp_die( -1 );
		}

		usercamp_create_default_fields();
		usercamp_create_default_forms();

		wp_die();
	}

	/**
	 * Create default custom fields.
	 */
	public static function create_fields() {

		check_ajax_referer( 'usercamp-create-fields', 'security' );

		if ( ! current_user_can( 'publish_uc_fields' ) ) {
			wp_die( -1 );
		}

		usercamp_create_default_fields();

		wp_die();
	}

	/**
	 * Create default user roles.
	 */
	public static function create_roles() {

		check_ajax_referer( 'usercamp-create-roles', 'security' );

		if ( ! current_user_can( 'publish_uc_roles' ) ) {
			wp_die( -1 );
		}

		usercamp_create_default_roles();

		wp_die();
	}

	/**
	 * Create default member lists.
	 */
	public static function create_memberlists() {

		check_ajax_referer( 'usercamp-create-memberlists', 'security' );

		if ( ! current_user_can( 'publish_uc_memberlists' ) ) {
			wp_die( -1 );
		}

		usercamp_create_default_memberlists();

		wp_die();
	}

	/**
	 * Duplicate a form.
	 */
	public static function duplicate_form() {

		check_ajax_referer( 'duplicate-form', 'security' );

		if ( ! current_user_can( 'publish_uc_forms' ) ) {
			wp_die( -1 );
		}

		$item = new UC_Form( absint( $_POST[ 'id' ] ) );
		$item->duplicate();

		wp_die();
	}

	/**
	 * Duplicate a custom field.
	 */
	public static function duplicate_field() {

		check_ajax_referer( 'duplicate-field', 'security' );

		if ( ! current_user_can( 'publish_uc_fields' ) ) {
			wp_die( -1 );
		}

		$item = new UC_Field( absint( $_POST[ 'id' ] ) );
		$item->duplicate();

		wp_die();
	}

	/**
	 * Duplicate a user role.
	 */
	public static function duplicate_role() {

		check_ajax_referer( 'duplicate-role', 'security' );

		if ( ! current_user_can( 'publish_uc_roles' ) ) {
			wp_die( -1 );
		}

		$item = new UC_Role( absint( $_POST[ 'id' ] ) );
		$item->duplicate();

		wp_die();
	}

	/**
	 * Duplicate a member list.
	 */
	public static function duplicate_memberlist() {

		check_ajax_referer( 'duplicate-memberlist', 'security' );

		if ( ! current_user_can( 'publish_uc_memberlists' ) ) {
			wp_die( -1 );
		}

		$item = new UC_Memberlist( absint( $_POST[ 'id' ] ) );
		$item->duplicate();

		wp_die();
	}

}

UC_AJAX::init();