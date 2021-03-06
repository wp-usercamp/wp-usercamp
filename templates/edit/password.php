<?php
/**
 * Password field
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
				type="password" 
				name="<?php echo esc_attr( $field['key'] ); ?>" 
				id="<?php echo esc_attr( $field['key'] ); ?>" 
				value="" 
				class="<?php echo implode( ' ', $field['input_class'] ); ?>" 
				<?php echo implode( ' ', $field['attributes'] ); ?> 
		/> 

		<?php if ( empty( $field['hide_toggle'] ) ) : ?>
		<div class="uc-pw-visible tips is-hidden" data-tip="<?php _e( 'Show password', 'usercamp' ); ?>" data-show="<?php _e( 'Show password', 'usercamp' ); ?>" data-hide="<?php _e( 'Hide password', 'usercamp' ); ?>">
			<?php echo uc_svg_icon( 'eye-off' ); ?>
		</div>
		<?php endif; ?>

	</div>

	<?php if ( ! empty( $field['helper'] ) ) : ?>
		<div class="usercamp-helper"><?php echo wp_kses_post( $field['helper'] ); ?></div>
	<?php endif; ?>

</fieldset>