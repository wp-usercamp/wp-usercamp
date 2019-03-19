<?php
/**
 * Field Label
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="usercamp-label <?php echo esc_attr( implode( ' ', $field['title_class'] ) ); ?>">

	<?php if ( $the_form->icons == 'label' ) : ?>

		<span class="uc-icon">
			<i data-feather="<?php echo esc_attr( $field[ 'icon' ] ); ?>"></i>
		</span>

	<?php endif; ?>

	<label for="<?php echo esc_attr( $field['key'] ); ?>" class="<?php echo esc_attr( implode( ' ', $field['label_class'] ) ); ?>">
		<?php echo wp_kses_post( $field['label'] ); ?>
	</label>

</div>