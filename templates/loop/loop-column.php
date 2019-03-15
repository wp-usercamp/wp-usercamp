<?php
/**
 * Loop form column
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="usercamp-col grid-<?php echo esc_attr( $size ); ?>">

	<?php foreach( (array) $the_form->fields_in( $row, $col ) as $key => $data ) : $field = uc_get_field( $data ); ?>

	<?php uc_get_template( 'edit/' . $field['type'] . '.php', array( 'field' => $field ) ); ?>

	<?php endforeach; ?>

</div>