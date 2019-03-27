<?php
/**
 * Field Icon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( in_array( 'has-icon', $field['control_class'] ) ) : ?>

	<span class="uc-icon">
		<?php echo uc_svg_icon( esc_attr( $field[ 'icon' ] ) ); ?>
	</span>

<?php endif; ?>