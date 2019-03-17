<?php
/**
 * Textarea field
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<fieldset class="usercamp-field <?php echo esc_attr( implode( ' ', $field['field_class'] ) ); ?>">

	<?php uc_get_template( 'field/label.php', array( 'field' => $field ) ); ?>

	<div class="usercamp-input <?php echo esc_attr( implode( ' ', $field['control_class'] ) ); ?>">

		<?php uc_get_template( 'field/icon.php', array( 'field' => $field ) ); ?>

		<textarea 
				name="<?php echo $field['key']; ?>" 
				id="<?php echo $field['key']; ?>" 
				class="<?php echo esc_attr( implode( ' ', $field['input_class'] ) ); ?>" 
				cols="20" 
				rows="5" 
				<?php echo esc_attr( implode( ' ', $field['attributes'] ) ); ?>
		><?php echo esc_textarea( $field['value'] ); ?></textarea>

	</div>

</fieldset>