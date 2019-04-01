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

		<h4><?php _e( 'Installed Custom Fields', 'usercamp' ); ?></h4>
		<div class="uc-buttons">
			<p>
				<?php
				if ( ! empty( $fields = usercamp_get_custom_fields() ) ) {
					foreach( $fields as $key => $data ) {
						$label = empty( $data['label'] ) ? $key : $data['label'];
						?>
						<a href="#" class="button button-secondary insert_field" <?php echo uc_get_data_attributes( $data ); ?>>
							<?php echo esc_html( $label ); ?>
						</a>
						<?php
					}
				} else {
					?>
					<a href="#uc-create-fields" class="button button-primary">
						<?php echo esc_html( 'Create default custom fields', 'usercamp' ); ?>
					</a>
					<?php
				}
				?>
			</p>
		</div>

		<h4><?php _e( 'New Custom Field', 'usercamp' ); ?></h4>
		<div class="uc-buttons alt">
			<p>
				<?php foreach( usercamp_get_field_types() as $key => $data ) : ?>
					<a href="#uc-add-field" class="button button-primary new_field" data-type="<?php echo esc_attr( $key ); ?>" rel="modal:open">
						<?php echo uc_svg_icon( esc_attr( $data['icon'] ) ); ?>
						<?php echo esc_html( $data['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</p>
		</div>

		<h4><?php _e( 'New HTML Element', 'usercamp' ); ?></h4>
		<div class="uc-buttons">
			<p>
				<?php foreach( usercamp_get_field_types( 'html' ) as $key => $data ) : ?>
					<a href="#uc-add-field" class="button button-secondary new_field" data-type="<?php echo esc_attr( $key ); ?>" rel="modal:open">
						<?php echo esc_html( $data['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</p>
		</div>

	</div>

</div>