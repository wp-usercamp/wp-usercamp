<?php
/**
 * Textarea field
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<fieldset class="usercamp-field <?php echo implode( ' ', $field['field_class'] ); ?>">

	<?php uc_get_template( 'field/label.php', array( 'field' => $field ) ); ?>

	<div class="usercamp-input <?php echo implode( ' ', $field['control_class'] ); ?>">

		<?php uc_get_template( 'field/icon.php', array( 'field' => $field ) ); ?>

		<textarea 
				name="<?php echo esc_attr( $field['key'] ); ?>" 
				id="<?php echo esc_attr( $field['key'] ); ?>" 
				class="<?php echo implode( ' ', $field['input_class'] ); ?>" 
				cols="20" 
				rows="5" 
				<?php echo implode( ' ', $field['attributes'] ); ?>
		><?php echo esc_textarea( $field['value'] ); ?></textarea>

	</div>

</fieldset>