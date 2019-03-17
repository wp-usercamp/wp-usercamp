<?php
/**
 * Password field
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
				type="password" 
				name="<?php echo $field['key']; ?>" 
				id="<?php echo $field['key']; ?>" 
				value="" 
				class="<?php echo esc_attr( implode( ' ', $field['input_class'] ) ); ?>" 
				<?php echo esc_attr( implode( ' ', $field['attributes'] ) ); ?> 
		/> 

		<div class="uc-pw-visible tips is-hidden" data-tip="<?php _e( 'Show password', 'usercamp' ); ?>" data-show="<?php _e( 'Show password', 'usercamp' ); ?>" data-hide="<?php _e( 'Hide password', 'usercamp' ); ?>">
			<i data-feather="eye-off"></i>
		</div>

	</div>

</fieldset>