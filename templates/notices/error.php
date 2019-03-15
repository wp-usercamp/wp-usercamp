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

<ul class="usercamp-error" role="alert">
	<?php if ( count( $messages ) >= 2 ) : ?>
	<li class="usercamp-error-header"><?php _e( 'Please correct the following errors:', 'usercamp' ); ?></li>
	<?php endif; ?>
	<?php foreach ( $messages as $message ) : ?>
		<li>
			<?php
				echo uc_kses_notice( $message );
			?>
		</li>
	<?php endforeach; ?>
</ul>