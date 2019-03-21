<?php
/**
 * List tables: custom fields.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UC_Admin_List_Table', false ) ) {
	include_once 'abstract-class-uc-admin-list-table.php';
}

/**
 * UC_Admin_List_Table_Fields class.
 */
class UC_Admin_List_Table_Fields extends UC_Admin_List_Table {

	/**
	 * Post type.
	 */
	protected $list_table_type = 'uc_field';

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
		echo '<div class="usercamp-BlankState"><i data-feather="database"></i>';
		echo '<h2 class="usercamp-BlankState-message">' . esc_html__( 'Create unlimited custom fields to fully customize the content of login, registration and profile forms.', 'usercamp' ) . '</h2>';
		echo '<a class="usercamp-BlankState-cta button-primary button" href="' . esc_url( admin_url( 'post-new.php?post_type=uc_field' ) ) . '">' . esc_html__( 'Create a custom field', 'usercamp' ) . '</a>';
		echo '<a class="usercamp-BlankState-cta button" target="_blank" href="">' . esc_html__( 'Learn more about custom fields', 'usercamp' ) . '</a>';
		echo '</div>';
		if ( current_user_can( 'publish_uc_fields' ) ) {
			echo '<a href="#uc-create-fields" class="uc-page-title-action button button-primary">' . __( 'Create default custom fields', 'usercamp' ) . '</a>';
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
			$actions = uc_array_insert_after( 'inline hide-if-no-js', $actions, 'duplicate', sprintf( '<a href="' . admin_url( 'post.php?post=' . $post->ID . '&amp;action=duplicate&amp;_wpnonce=' . wp_create_nonce( 'duplicate-field' ) ) . '" class="duplicate_field" aria-label="%s">%s</a>', __( 'Duplicate this field', 'usercamp' ), __( 'Duplicate', 'usercamp' ) ) );
		}
		return $actions;
	}

	/**
	 * Define which columns are sortable.
	 */
	public function define_sortable_columns( $columns ) {
		$custom = array(
			'name'  	=> 'title',
			'key'		=> 'key',
			'type'		=> 'type',
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
		$show_columns['type']  		= __( 'Type', 'usercamp' );
		$show_columns['options']  	= __( 'Options', 'usercamp' );
		$show_columns['can_view']  	= __( 'Who can view?', 'usercamp' );
		$show_columns['uc_date']  	= __( 'Date Added', 'usercamp' );

		return $show_columns;
	}

	/**
	 * Pre-fetch any data for the row each column has access to it. global is there for bw compat.
	 */
	protected function prepare_row_data( $post_id ) {
		global $the_field;

		if ( empty( $this->object ) || $this->object->id !== $post_id ) {
			$this->object = new UC_Field( $post_id );
			$the_field = $this->object;
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
		if ( $this->object->key ) {
			echo '<span class="uc-tag">' . esc_html__( $this->object->key ) . '</span>';
		}
	}

	/**
	 * Render columm: type.
	 */
	protected function render_type_column() {
		if ( $this->object->type ) {
			echo uc_get_field_type( $this->object->type, 'html' );
		}
	}

	/**
	 * Render columm: options.
	 */
	protected function render_options_column() {

		// Required
		if ( $this->object->is_required ) {
			echo '<span class="uc-icon yes tips" data-tip="' . __( 'Required', 'usercamp' ) . '"><i data-feather="star"></i></span>';
		} else {
			echo '<span class="uc-icon no tips" data-tip="' . __( 'Optional', 'usercamp' ) . '"><i data-feather="star"></i></span>';
		}

		// Private
		if ( $this->object->is_private ) {
			echo '<span class="uc-icon yes tips" data-tip="' . __( 'Private', 'usercamp' ) . '"><i data-feather="shield"></i></span>';
		} else {
			echo '<span class="uc-icon no tips" data-tip="' . __( 'Public', 'usercamp' ) . '"><i data-feather="shield-off"></i></span>';
		}

		// Read only
		if ( $this->object->is_readonly ) {
			echo '<span class="uc-icon yes tips" data-tip="' . __( 'Read Only', 'usercamp' ) . '"><i data-feather="lock"></i></span>';
		} else {
			echo '<span class="uc-icon no tips" data-tip="' . __( 'Editable', 'usercamp' ) . '"><i data-feather="lock"></i></span>';
		}

	}

	/**
	 * Render columm: can_view.
	 */
	protected function render_can_view_column() {
		if ( $this->object->can_view ) {
			$can_view = array_merge( array( '_none' => __( 'No one', 'usercamp' ), 'owner' => __( 'Owner', 'usercamp' ) ), usercamp_get_roles() );
			foreach( $this->object->can_view as $value ) {
				echo '<span class="uc-tag">' . esc_html__( $can_view[ $value ] ) . '</span>';
			}
		} else {
			echo '<span class="uc-tag">' . esc_html__( 'Everyone', 'usercamp' ) . '</span>';
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

		if ( 'edit.php' !== $pagenow || 'uc_field' !== $typenow || ! get_query_var( 'field_search' ) || ! isset( $_GET['s'] ) ) {
			return $query;
		}

		return uc_clean( wp_unslash( $_GET['s'] ) );
	}

	/**
	 * Render any custom filters and search inputs for the list table.
	 */
	protected function render_filters() {
		?>
		<select name="field_type" id="dropdown_custom_field_type">
			<option value=""><?php esc_html_e( 'Show all types', 'usercamp' ); ?></option>
			<?php
			$types = usercamp_get_field_types();

			foreach ( $types as $name => $type ) {
				echo '<option value="' . esc_attr( $name ) . '"';

				if ( isset( $_GET['field_type'] ) ) {
					selected( $name, uc_clean( wp_unslash( $_GET['field_type'] ) ) );
				}

				echo '>' . esc_html( $type['label'] ) . '</option>';
			}
			?>
		</select>
		<?php
	}

	/**
	 * Handle any query filters.
	 */
	protected function query_filters( $query_vars ) {

		if ( ! empty( $_GET['field_type'] ) ) {
			$query_vars['meta_key']   = 'type';
			$query_vars['meta_value'] = uc_clean( wp_unslash( $_GET['field_type'] ) );
		}

		if ( isset( $query_vars['orderby'] ) ) {
			if ( 'key' === $query_vars['orderby'] ) {
				$query_vars = array_merge(
					$query_vars,
					array(
						'meta_key' => 'key',
						'orderby'  => 'meta_value',
					)
				);
			}
			if ( 'type' === $query_vars['orderby'] ) {
				$query_vars = array_merge(
					$query_vars,
					array(
						'meta_key' => 'type',
						'orderby'  => 'meta_value',
					)
				);
			}
		}

		// Search.
		if ( ! empty( $query_vars['s'] ) ) {
			$data_store							= UC_Data_Store::load( 'field' );
			$ids								= $data_store->search_fields( uc_clean( wp_unslash( $_GET['s'] ) ) );
			$query_vars['post__in']       		= array_merge( $ids, array( 0 ) );
			$query_vars['field_search'] 		= true;
			unset( $query_vars['s'] );
		}

		return $query_vars;
	}

}