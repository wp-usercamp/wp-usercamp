<?php
/**
 * Email field
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<fieldset class="usercamp-field">

	<div class="usercamp-label">
		<label for="<?php echo esc_attr( $field['key'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?></label>
	</div>

	<div class="usercamp-input">
		<input type="text" name="<?php echo $field['key']; ?>" id="<?php echo $field['key']; ?>" value="<?php echo esc_attr( $field['value'] ); ?>" class="<?php echo esc_attr( implode( ' ', $field['classes'] ) ); ?>" /> 
	</div>

</fieldset>