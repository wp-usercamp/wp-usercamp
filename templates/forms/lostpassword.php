<?php
/**
 * Lost password form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $the_form->row_count <= 0 ) {
	return;
}

?>

<form class="usercamp-lostpassword" action="" method="post" accept-charset="utf-8">

	<?php uc_print_notices(); ?>

	<div class="usercamp-text"><?php echo esc_html( $atts[ 'top_note' ] ); ?></div>

	<?php uc_form_loop_edit(); ?>

	<div class="usercamp-buttons">
		<a href="#" class="usercamp-button main"><?php echo esc_html( $atts[ 'retrieve_password' ] ); ?></a>
		<a href="#" class="usercamp-button alt"><?php echo esc_html( $atts[ 'log_in' ] ); ?></a>
	</div>

	<?php wp_nonce_field( 'usercamp-lost-password', 'usercamp-lost-password-nonce' ); ?>

</form>