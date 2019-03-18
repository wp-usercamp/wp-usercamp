<?php
/**
 * Displays the top note.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( $top_note ) : ?>

	<div class="usercamp-text"><?php echo esc_html( $top_note ); ?></div>

<?php endif; ?>