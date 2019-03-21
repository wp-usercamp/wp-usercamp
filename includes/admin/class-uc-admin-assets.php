<?php
/**
 * Load assets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Admin_Assets Class.
 */
class UC_Admin_Assets {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueue styles.
	 */
	public function admin_styles() {
		global $wp_scripts;

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		// Register admin styles.
		wp_register_style( 'usercamp_admin_menu_styles', uc()->plugin_url() . '/assets/css/menu.css', array(), UC_VERSION );
		wp_register_style( 'usercamp_admin_styles', uc()->plugin_url() . '/assets/css/admin.css', array(), UC_VERSION );

		// Sitewide menu CSS.
		wp_enqueue_style( 'usercamp_admin_menu_styles' );

		// Admin styles for plugin pages only.
		if ( in_array( $screen_id, uc_get_screen_ids() ) ) {
			wp_enqueue_style( 'usercamp_admin_styles' );
		}

	}	

	/**
	 * Enqueue scripts.
	 */
	public function admin_scripts() {
		global $wp_query, $post;

		$screen       = get_current_screen();
		$screen_id    = $screen ? $screen->id : '';

		// Register scripts.
		wp_register_script( 'jquery-helper', uc()->plugin_url() . '/assets/js/jquery-helper/jquery-helper.js', array( 'jquery' ), UC_VERSION, true );
		wp_register_script( 'jquery-selectize', uc()->plugin_url() . '/assets/js/jquery-selectize/jquery-selectize.js', array( 'jquery' ), UC_VERSION, true );
		wp_register_script( 'jquery-tiptip', uc()->plugin_url() . '/assets/js/jquery-tiptip/jquery-tiptip.js', array( 'jquery' ), UC_VERSION, true );
		wp_register_script( 'jquery-toggles', uc()->plugin_url() . '/assets/js/jquery-toggles/jquery-toggles.js', array( 'jquery' ), UC_VERSION, true );
		wp_register_script( 'jquery-modal', uc()->plugin_url() . '/assets/js/jquery-modal/jquery-modal.js', array( 'jquery' ), UC_VERSION, true );
		wp_register_script( 'jquery-feather', uc()->plugin_url() . '/assets/js/jquery-feather/jquery-feather.js', array( 'jquery' ), UC_VERSION, true );
		wp_register_script( 'usercamp_admin', uc()->plugin_url() . '/assets/js/admin/usercamp_admin.js', array( 'jquery', 'jquery-ui-sortable', 'jquery-helper', 'jquery-selectize', 'jquery-tiptip', 'jquery-toggles', 'jquery-modal', 'jquery-feather' ), UC_VERSION, true );

		// Usercamp admin pages.
		if ( in_array( $screen_id, uc_get_screen_ids() ) ) {
			wp_enqueue_script( 'usercamp_admin' );

			$params = array(
				'ajax_url'	=> admin_url( 'admin-ajax.php' ),
				'fields'	=> uc_get_field_opts(),
				'nonces'	=> array(
					'save_form'				=> wp_create_nonce( 'usercamp-save-form' ),
					'create_forms' 			=> wp_create_nonce( 'usercamp-create-forms' ),
					'create_fields' 		=> wp_create_nonce( 'usercamp-create-fields' ),
					'create_roles'			=> wp_create_nonce( 'usercamp-create-roles' ),
					'create_memberlists'	=> wp_create_nonce( 'usercamp-create-memberlists' ),
					'add_field'				=> wp_create_nonce( 'usercamp-add-field' ),
				),
				'states'	=> array(
					'create_forms'			=> esc_html__( 'Creating forms...', 'usercamp' ),
					'create_fields'			=> esc_html__( 'Creating fields...', 'usercamp' ),
					'create_roles'			=> esc_html__( 'Creating roles...', 'usercamp' ),
					'create_memberlists'	=> esc_html__( 'Creating member lists...', 'usercamp' ),
					'done_redirect'			=> esc_html__( 'That&#39;s It! Please hold on.', 'usercamp' ),
					'show_less'				=> esc_html__( 'Show less', 'usercamp' ),
					'show_more'				=> esc_html__( 'Show more', 'usercamp' ),
					'ajax_error'			=> esc_html__( 'An error has occured.', 'usercamp' ),
					'duplicating'			=> esc_html__( 'Duplicating...', 'usercamp' ),
					'saving_changes'		=> esc_html__( 'Saving changes...', 'usercamp' ),
					'unsaved_changes'		=> __( 'Your changes will not take effect until you press on <b>Save changes &rarr;</b> button.', 'usercamp' ),
					'saved_changes'			=> esc_html__( 'Changes have been saved.', 'usercamp' ),
				),
				'modal'		=> array(
					'creating'				=> esc_html__( 'Create a Custom Field', 'usercamp' ),
					'editing'				=> esc_html__( 'Editing "{field}"', 'usercamp' ),
					'save_button'			=> esc_html__( 'Save Field &rarr;', 'usercamp' ),
					'create_button'			=> esc_html__( 'Create &rarr;', 'usercamp' ),
				),
			);

			wp_localize_script( 'usercamp_admin', 'usercamp_admin', $params );
		}

	}

}

return new UC_Admin_Assets();