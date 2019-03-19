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

		<i data-feather="<?php echo esc_attr( $field[ 'icon' ] ); ?>"></i>

	</span>

<?php endif; ?>