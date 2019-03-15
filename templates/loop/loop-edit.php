<?php
/**
 * Loop for edit mode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php for ( $row = 1; $row <= $the_form->row_count; $row++ ) : ?>

	<?php uc_form_loop_start(); ?>

		<?php for ( $col = 0; $col < $the_form->cols[$row]['count']; $col++ ) : ?>

			<?php uc_form_loop_column( array(
				'row' 	=> $row,
				'col'	=> $col,
				'size'	=> $the_form->cols[$row]['layout'][$col]
			) ); ?>

		<?php endfor; ?>

	<?php uc_form_loop_end(); ?>

<?php endfor; ?>