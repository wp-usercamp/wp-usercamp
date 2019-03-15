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
	<?php foreach ( $messages as $message ) : ?>
		<li>
			<?php
				echo uc_kses_notice( $message );
			?>
		</li>
	<?php endforeach; ?>
</ul>