<?php
/**
 * Text field
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<fieldset class="usercamp-field <?php echo esc_attr( implode( ' ', $field['field_class'] ) ); ?>">

	<?php uc_get_template( 'field/label.php', array( 'field' => $field ) ); ?>

	<div class="usercamp-input <?php echo esc_attr( implode( ' ', $field['control_class'] ) ); ?>">

		<?php uc_get_template( 'field/icon.php', array( 'field' => $field ) ); ?>

		<input 
				type="text" 
				name="<?php echo $field['key']; ?>" 
				id="<?php echo $field['key']; ?>" 
				value="<?php echo esc_attr( $field['value'] ); ?>" 
				class="<?php echo esc_attr( implode( ' ', $field['input_class'] ) ); ?>" 
				<?php echo esc_attr( implode( ' ', $field['attributes'] ) ); ?> 
		/> 

	</div>

</fieldset>