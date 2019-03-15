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

	<div class="usercamp-text"><?php _e( 'Please write your email in the box below and we’ll send you a link to the password reset page.', 'usercamp' ); ?></div>

	<?php uc_form_loop_edit(); ?>

	<div class="usercamp-buttons">
		<a href="#" class="usercamp-button main"><?php _e( 'Retrieve Password', 'usercamp' ); ?></a>
		<a href="#" class="usercamp-button alt"><?php _e( 'Wait, I remember!', 'usercamp' ); ?></a>
	</div>

	<?php wp_nonce_field( 'usercamp-lost-password', 'usercamp-lost-password-nonce' ); ?>

</form>