<?php
/**
 * Modal: Add element to form.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="modal" id="uc-add-element">

	<h3><?php _e( 'Add Element', 'usercamp' ); ?></h3>

	<div class="modal-content">

		<h4><?php _e( 'Custom Fields', 'usercamp' ); ?></h4>
		<div class="uc-buttons"><p>
			<?php
			if ( ! empty( $fields = usercamp_get_custom_fields() ) ) {
				foreach( $fields as $key => $data ) {
					$label = empty( $data['label'] ) ? $key : $data['label'];
					echo '<a href="#" class="button button-secondary insert_field" ' . uc_get_data_attributes( $data ) . '>' . esc_html( $label ) . '</a>';
				}
			} else {
				echo '<a href="#uc-create-fields" class="button button-primary">' . __( 'Create default custom fields', 'usercamp' ) . '</a>';
			}
			?>
		</p></div>

		<h4><?php _e( 'New Custom Field', 'usercamp' ); ?></h4>
		<div class="uc-buttons alt"><p>
			<?php
			foreach( $new = usercamp_get_field_types() as $key => $data ) {
				echo '<a href="#uc-add-field" class="button button-primary new_field" data-type="' . esc_attr( $key ) . '" rel="modal:open">' . uc_svg_icon( esc_attr( $data['icon'] ) ) . esc_html__( $data['label'] ) . '</a>';
			}
			?>
		</p></div>

	</div>

</div>