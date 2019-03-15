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

<div class="usercamp-lostpassword">

	<?php _e( 'Please write your email in the box below and we’ll send you a link to the password reset page.', 'usercamp' ); ?>

	<?php uc_form_loop_edit(); ?>

</div>