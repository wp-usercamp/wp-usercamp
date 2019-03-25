<?php
/**
 * Field Label
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( ! empty( $field['label'] ) ) : ?>

<div class="usercamp-label <?php echo esc_attr( implode( ' ', $field['title_class'] ) ); ?>">

	<?php if ( in_array( 'has-icon', $field['title_class'] ) ) : ?>

		<span class="uc-icon">
			<i data-feather="<?php echo esc_attr( $field[ 'icon' ] ); ?>"></i>
		</span>

	<?php endif; ?>

	<label for="<?php echo esc_attr( $field['key'] ); ?>" class="<?php echo esc_attr( implode( ' ', $field['label_class'] ) ); ?>">
		<?php echo esc_html( $field['label'] ); ?>
	</label>

</div>

<?php endif; ?>