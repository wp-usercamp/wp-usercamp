<?php
/**
 * Show messages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! $messages ) {
	return;
}

?>

<?php foreach ( $messages as $message ) : ?>
	<div class="usercamp-info">
		<?php
			echo uc_kses_notice( $message );
		?>
	</div>
<?php endforeach; ?>