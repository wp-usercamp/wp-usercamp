<?php
/**
 * Form Buttons.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( ! empty( $first_button ) ) : ?>

<div class="usercamp-buttons usercamp-butttons-<?php echo ! empty( $second_button ) ? 2 : 1; ?>">

	<a href="#" class="usercamp-button main">
		<?php echo esc_html( $first_button ); ?>
	</a>

	<?php if ( ! empty( $second_button ) ) : ?>

		<a href="#" class="usercamp-button alt">
			<?php echo esc_html( $second_button ); ?>
		</a>

	<?php endif; ?>

</div>

<?php endif; ?>