<?php
/**
 * Post Types Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Admin_Post_Types class.
 */
class UC_Admin_Post_Types {

	/**
	 * Constructor.
	 */
	public function __construct() {
		include_once dirname( __FILE__ ) . '/class-uc-admin-meta-boxes.php';

		// Load correct list table classes for current screen.
		add_action( 'current_screen', array( $this, 'setup_screen' ) );
		add_action( 'check_ajax_referer', array( $this, 'setup_screen' ) );

		// Admin notices.
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_filter( 'bulk_post_updated_messages', array( $this, 'bulk_post_updated_messages' ), 10, 2 );

		// Extra post data and screen elements.
		add_action( 'edit_form_top', array( $this, 'edit_form_top' ) );
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

		// Fire when specific post type is deleted.
		add_action( 'before_delete_post', array( $this, 'before_delete_post' ) );
	}

	/**
	 * Looks at the current screen and loads the correct list table handler.
	 */
	public function setup_screen() {
		global $uc_list_table;

		$screen_id = false;

		if ( function_exists( 'get_current_screen' ) ) {
			$screen    = get_current_screen();
			$screen_id = isset( $screen, $screen->id ) ? $screen->id : '';
		}

		if ( ! empty( $_REQUEST['screen'] ) ) {
			$screen_id = uc_clean( wp_unslash( $_REQUEST['screen'] ) );
		}

		switch ( $screen_id ) {
			case 'edit-uc_form':
				include_once 'list-tables/class-uc-admin-list-table-forms.php';
				$uc_list_table = new UC_Admin_List_Table_Forms();
				break;
			case 'edit-uc_field':
				include_once 'list-tables/class-uc-admin-list-table-fields.php';
				$uc_list_table = new UC_Admin_List_Table_Fields();
				break;
			case 'edit-uc_role':
				include_once 'list-tables/class-uc-admin-list-table-roles.php';
				$uc_list_table = new UC_Admin_List_Table_Roles();
				break;
			case 'edit-uc_memberlist':
				include_once 'list-tables/class-uc-admin-list-table-memberlists.php';
				$uc_list_table = new UC_Admin_List_Table_Memberlists();
				break;
		}

		// Ensure the table handler is only loaded once. Prevents multiple loads if a plugin calls check_ajax_referer many times.
		remove_action( 'current_screen', array( $this, 'setup_screen' ) );
		remove_action( 'check_ajax_referer', array( $this, 'setup_screen' ) );
	}

	/**
	 * Change messages when a post type is updated.
	 */
	public function post_updated_messages( $messages ) {
		global $post;

		$messages['uc_form'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Form updated.', 'usercamp' ),
			2  => __( 'Custom field updated.', 'usercamp' ),
			3  => __( 'Custom field deleted.', 'usercamp' ),
			4  => __( 'Form updated.', 'usercamp' ),
			5  => __( 'Revision restored.', 'usercamp' ),
			6  => __( 'Form updated.', 'usercamp' ),
			7  => __( 'Form saved.', 'usercamp' ),
			8  => __( 'Form submitted.', 'usercamp' ),
			9  => sprintf(
				__( 'Form scheduled for: %s.', 'usercamp' ),
				'<strong>' . date_i18n( __( 'M j, Y @ G:i', 'usercamp' ), strtotime( $post->post_date ) ) . '</strong>'
			),
			10 => __( 'Form draft updated.', 'usercamp' ),
			11 => __( 'Form updated and sent.', 'usercamp' ),
		);

		$messages['uc_field'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Custom field updated.', 'usercamp' ),
			2  => __( 'Custom field updated.', 'usercamp' ),
			3  => __( 'Custom field deleted.', 'usercamp' ),
			4  => __( 'Custom field updated.', 'usercamp' ),
			5  => __( 'Revision restored.', 'usercamp' ),
			6  => __( 'Custom field updated.', 'usercamp' ),
			7  => __( 'Custom field saved.', 'usercamp' ),
			8  => __( 'Custom field submitted.', 'usercamp' ),
			9  => sprintf(
				__( 'Custom field scheduled for: %s.', 'usercamp' ),
				'<strong>' . date_i18n( __( 'M j, Y @ G:i', 'usercamp' ), strtotime( $post->post_date ) ) . '</strong>'
			),
			10 => __( 'Custom field draft updated.', 'usercamp' ),
			11 => __( 'Custom field updated and sent.', 'usercamp' ),
		);

		$messages['uc_role'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'User role updated.', 'usercamp' ),
			2  => __( 'Custom field updated.', 'usercamp' ),
			3  => __( 'Custom field deleted.', 'usercamp' ),
			4  => __( 'User role updated.', 'usercamp' ),
			5  => __( 'Revision restored.', 'usercamp' ),
			6  => __( 'User role updated.', 'usercamp' ),
			7  => __( 'User role saved.', 'usercamp' ),
			8  => __( 'User role submitted.', 'usercamp' ),
			9  => sprintf(
				__( 'User role scheduled for: %s.', 'usercamp' ),
				'<strong>' . date_i18n( __( 'M j, Y @ G:i', 'usercamp' ), strtotime( $post->post_date ) ) . '</strong>'
			),
			10 => __( 'User role draft updated.', 'usercamp' ),
			11 => __( 'User role updated and sent.', 'usercamp' ),
		);

		$messages['uc_memberlist'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Member list updated.', 'usercamp' ),
			2  => __( 'Custom field updated.', 'usercamp' ),
			3  => __( 'Custom field deleted.', 'usercamp' ),
			4  => __( 'Member list updated.', 'usercamp' ),
			5  => __( 'Revision restored.', 'usercamp' ),
			6  => __( 'Member list updated.', 'usercamp' ),
			7  => __( 'Member list saved.', 'usercamp' ),
			8  => __( 'Member list submitted.', 'usercamp' ),
			9  => sprintf(
				__( 'Member list scheduled for: %s.', 'usercamp' ),
				'<strong>' . date_i18n( __( 'M j, Y @ G:i', 'usercamp' ), strtotime( $post->post_date ) ) . '</strong>'
			),
			10 => __( 'Member list draft updated.', 'usercamp' ),
			11 => __( 'Member list updated and sent.', 'usercamp' ),
		);

		return $messages;
	}

	/**
	 * Specify custom bulk actions messages for different post types.
	 */
	public function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {
		$bulk_messages['uc_form'] = array(
			'updated'   => _n( '%s form updated.', '%s forms updated.', $bulk_counts['updated'], 'usercamp' ),
			'locked'    => _n( '%s form not updated, somebody is editing it.', '%s forms not updated, somebody is editing them.', $bulk_counts['locked'], 'usercamp' ),
			'deleted'   => _n( '%s form permanently deleted.', '%s forms permanently deleted.', $bulk_counts['deleted'], 'usercamp' ),
			'trashed'   => _n( '%s form moved to the Trash.', '%s forms moved to the Trash.', $bulk_counts['trashed'], 'usercamp' ),
			'untrashed' => _n( '%s form restored from the Trash.', '%s forms restored from the Trash.', $bulk_counts['untrashed'], 'usercamp' ),
		);

		$bulk_messages['uc_field'] = array(
			'updated'   => _n( '%s custom field updated.', '%s custom fields updated.', $bulk_counts['updated'], 'usercamp' ),
			'locked'    => _n( '%s custom field not updated, somebody is editing it.', '%s custom fields not updated, somebody is editing them.', $bulk_counts['locked'], 'usercamp' ),
			'deleted'   => _n( '%s custom field permanently deleted.', '%s custom fields permanently deleted.', $bulk_counts['deleted'], 'usercamp' ),
			'trashed'   => _n( '%s custom field moved to the Trash.', '%s custom fields moved to the Trash.', $bulk_counts['trashed'], 'usercamp' ),
			'untrashed' => _n( '%s custom field restored from the Trash.', '%s custom fields restored from the Trash.', $bulk_counts['untrashed'], 'usercamp' ),
		);

		$bulk_messages['uc_role'] = array(
			'updated'   => _n( '%s user role updated.', '%s user roles updated.', $bulk_counts['updated'], 'usercamp' ),
			'locked'    => _n( '%s user role not updated, somebody is editing it.', '%s user roles not updated, somebody is editing them.', $bulk_counts['locked'], 'usercamp' ),
			'deleted'   => _n( '%s user role permanently deleted.', '%s user roles permanently deleted.', $bulk_counts['deleted'], 'usercamp' ),
			'trashed'   => _n( '%s user role moved to the Trash.', '%s user roles moved to the Trash.', $bulk_counts['trashed'], 'usercamp' ),
			'untrashed' => _n( '%s user role restored from the Trash.', '%s user roles restored from the Trash.', $bulk_counts['untrashed'], 'usercamp' ),
		);

		$bulk_messages['uc_memberlist'] = array(
			'updated'   => _n( '%s member list updated.', '%s member lists updated.', $bulk_counts['updated'], 'usercamp' ),
			'locked'    => _n( '%s member list not updated, somebody is editing it.', '%s member lists not updated, somebody is editing them.', $bulk_counts['locked'], 'usercamp' ),
			'deleted'   => _n( '%s member list permanently deleted.', '%s member lists permanently deleted.', $bulk_counts['deleted'], 'usercamp' ),
			'trashed'   => _n( '%s member list moved to the Trash.', '%s member lists moved to the Trash.', $bulk_counts['trashed'], 'usercamp' ),
			'untrashed' => _n( '%s member list restored from the Trash.', '%s member lists restored from the Trash.', $bulk_counts['untrashed'], 'usercamp' ),
		);

		return $bulk_messages;
	}

	/**
	 * Output extra data on post forms.
	 */
	public function edit_form_top( $post ) {
		echo '<input type="hidden" id="original_post_title" name="original_post_title" value="' . esc_attr( $post->post_title ) . '" />';
	}

	/**
	 * Change title boxes in admin.
	 */
	public function enter_title_here( $text, $post ) {
		switch ( $post->post_type ) {
			case 'uc_form':
				$text = esc_html__( 'e.g. Registration', 'usercamp' );
				break;
			case 'uc_field':
				$text = esc_html__( 'e.g. Location', 'usercamp' );
				break;
			case 'uc_role':
				$text = esc_html__( 'e.g. Premium member', 'usercamp' );
				break;
			case 'uc_memberlist':
				$text = esc_html__( 'e.g. Member Directory', 'usercamp' );
				break;
		}
		return $text;
	}

	/**
	 * Before a post is completely removed.
	 */
	public function before_delete_post( $post_id ) {
		global $post_type;
		if ( in_array( $post_type, uc_get_post_types() ) ) {
			$object = new $post_type( $post_id );
			$object->delete();
		}
	}

}

new UC_Admin_Post_Types();