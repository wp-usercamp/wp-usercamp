<?php
/**
 * Register form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $the_form->row_count <= 0 || ! $the_form->has_fields() ) {
	return;
}

?>

<form class="usercamp-register" action="" method="post" accept-charset="utf-8" data-ajax="<?php echo $the_form->use_ajax; ?>" data-id="<?php echo absint( $the_form->id ); ?>" autocomplete="off">

	<?php uc_print_notices(); ?>

	<?php if ( $atts['top_note'] ) : ?>
	<div class="usercamp-text"><?php echo esc_html( $atts[ 'top_note' ] ); ?></div>
	<?php endif; ?>

	<?php uc_form_loop_edit(); ?>

	<div class="usercamp-buttons">
		<a href="#" class="usercamp-button main"><?php echo esc_html( $atts[ 'register' ] ); ?></a>
		<a href="#" class="usercamp-button alt"><?php echo esc_html( $atts[ 'got_account' ] ); ?></a>
	</div>

	<?php wp_nonce_field( 'usercamp-register', 'usercamp-register-nonce' ); ?>

</form>