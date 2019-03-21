<?php
/**
 * List tables: user roles.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UC_Admin_List_Table', false ) ) {
	include_once 'abstract-class-uc-admin-list-table.php';
}

/**
 * UC_Admin_List_Table_Roles class.
 */
class UC_Admin_List_Table_Roles extends UC_Admin_List_Table {

	/**
	 * Post type.
	 */
	protected $list_table_type = 'uc_role';

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_filter( 'disable_months_dropdown', '__return_true' );
		add_filter( 'get_search_query', array( $this, 'search_label' ) );
	}

	/**
	 * Render blank state.
	 */
	protected function render_blank_state() {
		echo '<div class="usercamp-BlankState"><i data-feather="user-check"></i>';
		echo '<h2 class="usercamp-BlankState-message">' . esc_html__( 'Create custom user roles and groups and have full control over users access and what they can do.', 'usercamp' ) . '</h2>';
		echo '<a class="usercamp-BlankState-cta button-primary button" href="' . esc_url( admin_url( 'post-new.php?post_type=uc_role' ) ) . '">' . esc_html__( 'Create a new user role', 'usercamp' ) . '</a>';
		echo '<a class="usercamp-BlankState-cta button" target="_blank" href="">' . esc_html__( 'Learn more about user roles', 'usercamp' ) . '</a>';
		echo '</div>';
		if ( current_user_can( 'publish_uc_roles' ) ) {
			echo '<a href="#uc-create-roles" class="uc-page-title-action button button-primary">' . __( 'Create default user roles', 'usercamp' ) . '</a>';
		}
	}

	/**
	 * Define primary column.
	 */
	protected function get_primary_column() {
		return 'name';
	}

	/**
	 * Get row actions to show in the list table.
	 */
	protected function get_row_actions( $actions, $post ) {
		$actions = array_merge( array( 'id' => sprintf( __( 'ID: %d', 'usercamp' ), $post->ID ) ), $actions );
		if ( $post->post_status == 'publish' ) {
			$actions = uc_array_insert_after( 'inline hide-if-no-js', $actions, 'duplicate', sprintf( '<a href="' . admin_url( 'post.php?post=' . $post->ID . '&amp;action=duplicate&amp;_wpnonce=' . wp_create_nonce( 'duplicate-role' ) ) . '" class="duplicate_role" aria-label="%s">%s</a>', __( 'Duplicate this role', 'usercamp' ), __( 'Duplicate', 'usercamp' ) ) );
		}
		return $actions;
	}

	/**
	 * Define which columns are sortable.
	 */
	public function define_sortable_columns( $columns ) {
		$custom = array(
			'name'  	=> 'title',
			'uc_date'	=> 'date',
		);
		return wp_parse_args( $custom, $columns );
	}

	/**
	 * Define which columns to show on this screen.
	 */
	public function define_columns( $columns ) {
		$show_columns               = array();
		$show_columns['cb']         = $columns['cb'];
		$show_columns['name']  		= __( 'Name', 'usercamp' );
		$show_columns['key']  		= __( 'Key', 'usercamp' );
		$show_columns['caps']  		= __( 'Capabilities', 'usercamp' );
		$show_columns['uc_date']  	= __( 'Date Added', 'usercamp' );

		return $show_columns;
	}

	/**
	 * Pre-fetch any data for the row each column has access to it. global is there for bw compat.
	 */
	protected function prepare_row_data( $post_id ) {
		global $the_role;

		if ( empty( $this->object ) || $this->object->id !== $post_id ) {
			$this->object = new UC_Role( $post_id );
			$the_role = $this->object;
		}
	}

	/**
	 * Render column: name.
	 */
	protected function render_name_column() {
		global $post;

		$edit_link = get_edit_post_link( $this->object->id );
		$title     = _draft_or_post_title();

		echo '<strong><a class="row-title" href="' . esc_url( $edit_link ) . '">' . esc_html( $title ) . '</a>';
		_post_states( $post );
		echo '</strong>';

		get_inline_data( $post );

		/* Custom inline data. */
		echo '
			<div class="hidden" id="usercamp_inline_' . absint( $this->object->id ) . '">
			</div>
		';
	}

	/**
	 * Render columm: key.
	 */
	protected function render_key_column() {
		if ( $this->object->name ) {
			echo '<span class="uc-tag">' . esc_html__( $this->object->name ) . '</span>';
		}
	}

	/**
	 * Render columm: caps.
	 */
	protected function render_caps_column() {
		$morelink = null;

		$caps = $this->object->caps;

		if ( ! is_array( $caps ) ) {
			return;
		}

		// Sort view.
		asort( $caps );

		$c = 0;
		foreach( $caps as $cap => $bool ) {
			if ( uc_get_cap_title( $cap ) ) {
				$c++;
				if ( $c > 3 ) {
					echo '<span class="uc-tag hidden">' .  esc_html( uc_get_cap_title( $cap ) ) . '</span>';
					if ( ! $morelink ) {
						echo '<a href="#" class="uc-togglemore">' . __( 'Show more', 'usercamp' ) . '</a>';
						$morelink = true;
					}
				} else {
					echo '<span class="uc-tag">' .  esc_html( uc_get_cap_title( $cap ) ) . '</span>';
				}
			}
		}
	}

	/**
	 * Render columm: date.
	 */
	protected function render_uc_date_column() {
		echo uc_get_the_date();
	}

	/**
	 * Change the label when searching
	 */
	public function search_label( $query ) {
		global $pagenow, $typenow;

		if ( 'edit.php' !== $pagenow || 'uc_role' !== $typenow || ! get_query_var( 'role_search' ) || ! isset( $_GET['s'] ) ) {
			return $query;
		}

		return uc_clean( wp_unslash( $_GET['s'] ) );
	}

	/**
	 * Handle any query filters.
	 */
	protected function query_filters( $query_vars ) {

		if ( isset( $query_vars['orderby'] ) ) {
		}

		// Search.
		if ( ! empty( $query_vars['s'] ) ) {
			$data_store							= UC_Data_Store::load( 'role' );
			$ids								= $data_store->search_roles( uc_clean( wp_unslash( $_GET['s'] ) ) );
			$query_vars['post__in']       		= array_merge( $ids, array( 0 ) );
			$query_vars['role_search'] 			= true;
			unset( $query_vars['s'] );
		}

		return $query_vars;
	}

}