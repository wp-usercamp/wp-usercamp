<?php
/**
 * Field Icon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( $the_form->icons == 'inside' ) : ?>

	<span class="uc-icon">

		<i data-feather="<?php echo esc_attr( $field[ 'icon' ] ); ?>"></i>

	</span>

<?php endif; ?>