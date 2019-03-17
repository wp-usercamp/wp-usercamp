<?php
/**
 * Textarea field
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<fieldset class="usercamp-field <?php echo esc_attr( implode( ' ', $field['field_class'] ) ); ?>">

	<div class="usercamp-label">

		<label for="<?php echo esc_attr( $field['key'] ); ?>" class="<?php echo esc_attr( implode( ' ', $field['label_class'] ) ); ?>"><?php echo wp_kses_post( $field['label'] ); ?></label>

	</div>

	<div class="usercamp-input">

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