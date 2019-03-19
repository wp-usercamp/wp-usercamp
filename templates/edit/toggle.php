<?php
/**
 * Toggle field
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<fieldset class="usercamp-field <?php echo implode( ' ', $field['field_class'] ); ?>">

	<?php uc_get_template( 'field/label.php', array( 'field' => $field ) ); ?>

	<div class="usercamp-input <?php echo implode( ' ', $field['control_class'] ); ?>">

		<input 
				type="checkbox" 
				name="<?php echo esc_attr( $field['key'] ); ?>" 
				id="<?php echo esc_attr( $field['key'] ); ?>" 
				value="<?php echo esc_attr( $field['value'] ); ?>" 
				class="<?php echo implode( ' ', $field['input_class'] ); ?>" 
				<?php echo implode( ' ', $field['attributes'] ); ?> 
				<?php checked( $field['value'], 1, true ) ?>
		/>

		<div class="uc-toggle" data-toggle-on="<?php echo $field['value']; ?>"></div>

	</div>

</fieldset>