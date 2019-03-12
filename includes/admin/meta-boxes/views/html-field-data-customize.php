<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="customize_field_data" class="panel usercamp_options_panel hidden">

	<div class="options_group">
		<?php
		usercamp_wp_text_input(
			array(
				'id'          		=> 'edit_label',
				'value'       		=> $the_field->edit_label,
				'label'       		=> __( 'Editing title', 'usercamp' ),
				'placeholder' 		=> __( 'Leave blank for default', 'usercamp' ),
				'description' 		=> __( 'This is the label that is displayed when user is editing', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);

		usercamp_wp_text_input(
			array(
				'id'          		=> 'view_label',
				'value'       		=> $the_field->view_label,
				'label'       		=> __( 'Viewing title', 'usercamp' ),
				'placeholder' 		=> __( 'Leave blank for default', 'usercamp' ),
				'description' 		=> __( 'This is the label that is displayed when user is viewing', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);
		?>
	</div>

	<?php do_action( 'usercamp_field_data_customize_panel' ); ?>

</div>