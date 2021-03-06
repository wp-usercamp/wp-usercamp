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

$type 		= esc_attr( $the_form->type );
$endpoint 	= $the_form->get_endpoint();

?>

<?php do_action( "usercamp_before_{$type}_form" ); ?>

<form class="usercamp-form usercamp-<?php echo $type; ?> usercamp-<?php echo $endpoint; ?>" action="" method="post" accept-charset="utf-8" data-ajax="<?php echo $the_form->use_ajax; ?>" <?php uc_print_inline_styles(); ?>>

	<?php uc_print_notices(); ?>

	<?php uc_form_note( $atts ); ?>

	<?php do_action( "usercamp_before_{$type}_form_content" ); ?>

	<?php uc_form_edit( $atts ); ?>

	<?php do_action( "usercamp_after_{$type}_form_content" ); ?>

	<?php uc_form_buttons( $atts ); ?>

</form>

<?php do_action( "usercamp_after_{$type}_form" ); ?>