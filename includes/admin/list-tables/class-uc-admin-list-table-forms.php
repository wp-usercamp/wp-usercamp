<?php
/**
 * List tables: forms.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UC_Admin_List_Table', false ) ) {
	include_once 'abstract-class-uc-admin-list-table.php';
}

/**
 * UC_Admin_List_Table_Forms Class.
 */
class UC_Admin_List_Table_Forms extends UC_Admin_List_Table {

	/**
	 * Post type.
	 */
	protected $list_table_type = 'uc_form';

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
		echo '<div class="usercamp-BlankState"><i data-feather="file-text"></i>';
		echo '<h2 class="usercamp-BlankState-message">' . esc_html__( 'Create registration, login and profile forms using a robust and easy-to-use drag and drop form builder.', 'usercamp' ) . '</h2>';
		echo '<a class="usercamp-BlankState-cta button-primary button" href="' . esc_url( admin_url( 'post-new.php?post_type=uc_form' ) ) . '">' . esc_html__( 'Create your first form', 'usercamp' ) . '</a>';
		echo '<a class="usercamp-BlankState-cta button" target="_blank" href="">' . esc_html__( 'Learn more about forms', 'usercamp' ) . '</a>';
		echo '</div>';
		if ( current_user_can( 'publish_uc_forms' ) ) {
			echo '<a href="#uc-create-forms" class="uc-page-title-action button button-primary">' . __( 'Create default forms', 'usercamp' ) . '</a>';
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
			$actions = uc_array_insert_after( 'inline hide-if-no-js', $actions, 'duplicate', sprintf( '<a href="' . admin_url( 'post.php?post=' . $post->ID . '&amp;action=duplicate&amp;_wpnonce=' . wp_create_nonce( 'duplicate-form' ) ) . '" class="duplicate_form" aria-label="%s">%s</a>', __( 'Duplicate this form', 'usercamp' ), __( 'Duplicate', 'usercamp' ) ) );
		}
		return $actions;
	}

	/**
	 * Define which columns are sortable.
	 */
	public function define_sortable_columns( $columns ) {
		$custom = array(
			'name'  	=> 'title',
			'type'		=> 'type',
			'uc_date'	=> 'date',
		);
		return wp_parse_args( $custom, $columns );
	}

	/**
	 * Define which columns to show on this screen.
	 */
	public function define_columns( $columns ) {
		$show_columns 				= array();
		$show_columns['cb']			= $columns['cb'];
		$show_columns['name']  		= __( 'Name', 'usercamp' );
		$show_columns['type']  		= __( 'Type', 'usercamp' );
		$show_columns['shortcode']	= __( 'Shortcode', 'usercamp' );
		$show_columns['uc_date']  	= __( 'Date Added', 'usercamp' );

		return $show_columns;
	}

	/**
	 * Pre-fetch any data for the row each column has access to it. global is there for bw compat.
	 */
	protected function prepare_row_data( $post_id ) {
		global $the_form;

		if ( empty( $this->object ) || $this->object->id !== $post_id ) {
			$this->object = new UC_Form( $post_id );
			$the_form = $this->object;
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
	 * Render columm: type.
	 */
	protected function render_type_column() {
		if ( $this->object->type ) {
			echo uc_get_form_type( $this->object->type );
		}
	}

	/**
	 * Render columm: shortcode.
	 */
	protected function render_shortcode_column() {
		echo $this->object->get_shortcode();
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

		if ( 'edit.php' !== $pagenow || 'uc_form' !== $typenow || ! get_query_var( 'form_search' ) || ! isset( $_GET['s'] ) ) {
			return $query;
		}

		return uc_clean( wp_unslash( $_GET['s'] ) );
	}

	/**
	 * Render any custom filters and search inputs for the list table.
	 */
	protected function render_filters() {
		?>
		<select name="form_type" id="dropdown_form_type_type">
			<option value=""><?php esc_html_e( 'Show all types', 'usercamp' ); ?></option>
			<?php
			$types = usercamp_get_form_types();

			foreach ( $types as $name => $type ) {
				echo '<option value="' . esc_attr( $name ) . '"';

				if ( isset( $_GET['form_type'] ) ) {
					selected( $name, uc_clean( wp_unslash( $_GET['form_type'] ) ) );
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

		if ( ! empty( $_GET['form_type'] ) ) {
			$query_vars['meta_key']   = 'type';
			$query_vars['meta_value'] = uc_clean( wp_unslash( $_GET['form_type'] ) );
		}

		if ( isset( $query_vars['orderby'] ) ) {
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
			$data_store							= UC_Data_Store::load( 'form' );
			$ids								= $data_store->search_forms( uc_clean( wp_unslash( $_GET['s'] ) ) );
			$query_vars['post__in']       		= array_merge( $ids, array( 0 ) );
			$query_vars['form_search'] 			= true;
			unset( $query_vars['s'] );
		}

		return $query_vars;
	}

}