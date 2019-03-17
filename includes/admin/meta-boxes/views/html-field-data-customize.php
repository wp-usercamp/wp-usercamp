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

		usercamp_wp_text_input(
			array(
				'id'          		=> 'icon',
				'value'       		=> $the_field->icon,
				'label'       		=> __( 'Icon', 'usercamp' ),
				'description' 		=> __( 'For full list of icons please check <a href="https://feathericons.com/">https://feathericons.com</a>', 'usercamp' ),
			)
		);

		usercamp_wp_text_input(
			array(
				'id'          		=> 'placeholder',
				'value'       		=> $the_field->placeholder,
				'label'       		=> __( 'Placeholder', 'usercamp' ),
				'description' 		=> __( 'When applicable, this text will appear in case the user does not give any input.', 'usercamp' ),
			)
		);

		usercamp_wp_textarea(
			array(
				'id'          		=> 'help',
				'value'       		=> $the_field->help,
				'label'       		=> __( 'Help / Instructions', 'usercamp' ),
				'rows'				=> 3,
				'description'		=> __( 'This appears below the field. Use this If you want to instruct the user how to fill the field.', 'usercamp' ),
			)
		);
		?>
	</div>

	<?php do_action( 'usercamp_field_data_customize_panel' ); ?>

</div>