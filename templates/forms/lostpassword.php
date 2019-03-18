<?php
/**
 * Lost password form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $the_form->row_count <= 0 || ! $the_form->has_fields() ) {
	return;
}

?>

<form class="usercamp-lostpassword" action="" method="post" accept-charset="utf-8" data-ajax="<?php echo $the_form->use_ajax; ?>" data-id="<?php echo absint( $the_form->id ); ?>" <?php uc_print_inline_styles(); ?>>

	<?php uc_print_notices(); ?>

	<?php do_action( 'usercamp_lostpassword_shortcode_start' ); ?>

	<?php uc_form_loop_note( $atts ); ?>

	<?php uc_form_loop_edit(); ?>

	<div class="usercamp-buttons">
		<a href="#" class="usercamp-button main"><?php echo esc_html( $atts[ 'retrieve_password' ] ); ?></a>
		<a href="#" class="usercamp-button alt"><?php echo esc_html( $atts[ 'log_in' ] ); ?></a>
	</div>

	<?php wp_nonce_field( 'usercamp-lostpassword', 'usercamp-lostpassword-nonce' ); ?>

	<?php do_action( 'usercamp_lostpassword_shortcode_end' ); ?>

</form>