<?php
/**
 * Show messages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}

?>

<?php uc_checkmark(); ?>

<?php foreach ( $messages as $message ) : ?>
	<div class="usercamp-message" role="alert">
		<?php
			echo uc_kses_notice( $message );
		?>
	</div>
<?php endforeach; ?>