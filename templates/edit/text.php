<?php
/**
 * Text field
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<fieldset class="usercamp-field <?php echo implode( ' ', $field['field_class'] ); ?>">

	<?php uc_get_template( 'field/label.php', array( 'field' => $field ) ); ?>

	<div class="usercamp-input <?php echo implode( ' ', $field['control_class'] ); ?>">

		<?php uc_get_template( 'field/icon.php', array( 'field' => $field ) ); ?>

		<input 
				type="text" 
				name="<?php echo esc_attr( $field['key'] ); ?>" 
				id="<?php echo esc_attr( $field['key'] ); ?>" 
				value="<?php echo esc_attr( $field['value'] ); ?>" 
				class="<?php echo implode( ' ', $field['input_class'] ); ?>" 
				<?php echo implode( ' ', $field['attributes'] ); ?> 
		/> 

	</div>

</fieldset>