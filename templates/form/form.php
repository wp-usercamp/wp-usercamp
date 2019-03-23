<?php
/**
 * Form Template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $the_form->has_fields() ) {
	return;
}

$type = esc_attr( $the_form->type );

?>

<?php do_action( "usercamp_before_{$type}_form" ); ?>

<form class="usercamp-form usercamp-<?php echo $type; ?>" action="" method="post" accept-charset="utf-8" data-ajax="<?php echo $the_form->use_ajax; ?>" data-id="<?php echo absint( $the_form->id ); ?>" <?php uc_print_inline_styles(); ?>>

	<?php uc_print_notices(); ?>

	<?php do_action( "usercamp_{$type}_shortcode_start" ); ?>

	<?php uc_form_note( $atts ); ?>

	<?php uc_form_edit( $atts ); ?>

	<?php uc_form_buttons( $atts ); ?>

	<?php wp_nonce_field( "usercamp-{$type}", "usercamp-{$type}-nonce" ); ?>

	<?php do_action( "usercamp_{$type}_shortcode_end" ); ?>

</form>

<?php do_action( "usercamp_after_{$type}_form" ); ?>