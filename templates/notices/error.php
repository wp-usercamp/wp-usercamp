<?php
/**
 * Show error messages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}

?>

<?php if ( count( $messages ) > 1 ) : ?>

	<ul class="usercamp-error" role="alert">
		<li class="usercamp-error-header"><?php _e( 'Please correct the following errors:', 'usercamp' ); ?></li>
		<?php foreach ( $messages as $message ) : ?>
			<li>
				<?php
					echo uc_kses_notice( $message );
				?>
			</li>
		<?php endforeach; ?>
	</ul>

<?php else : ?>

	<?php foreach ( $messages as $message ) : ?>
		<div class="usercamp-error" role="alert">
			<?php
				echo uc_kses_notice( $message );
			?>
		</div>
	<?php endforeach; ?>

<?php endif; ?>