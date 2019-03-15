<?php
/**
 * Loop form column
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="usercamp-col grid-<?php echo esc_attr( $size ); ?>">

	<?php foreach( (array) $the_form->fields_in( $row, $col ) as $key => $field ) : ?>

	<?php endforeach; ?>

</div>