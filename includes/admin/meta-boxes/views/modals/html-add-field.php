<?php
/**
 * Modal: Add a custom field.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="modal" id="uc-add-field">

	<h3><?php _e( 'Create a Custom Field', 'usercamp' ); ?></h3>

	<?php uc_init_custom_field_options(); ?>

	<div class="modal-footer">
		<a href="#" class="button button-primary add_field"><?php _e( 'Create &rarr;', 'usercamp' ); ?></a>
		<a href="#uc-add-element" class="button button-secondary" rel="modal:open"><?php _e( '&larr; Cancel', 'usercamp' ); ?></a>
	</div>

</div>