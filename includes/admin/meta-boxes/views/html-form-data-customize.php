<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="customize_form_data" class="panel usercamp_options_panel hidden">

	<div class="options_group">
		<?php
		usercamp_wp_text_input(
			array(
				'id'          		=> 'font_size',
				'value'       		=> $the_form->font_size,
				'label'       		=> __( 'Custom font size', 'usercamp' ),
				'description' 		=> __( 'Leave blank to use theme default. If you specify a font-size here the form will use it as a base for all form elements.', 'usercamp' ),
				'desc_tip'			=> true,
				'style'				=> 'width: 60px;',
			)
		);

		usercamp_wp_text_input(
			array(
				'id'          		=> 'max_width',
				'value'       		=> $the_form->max_width,
				'label'       		=> __( 'Maximum form width', 'usercamp' ),
				'style'				=> 'width: 60px;',
			)
		);
		?>
	</div>

	<?php do_action( 'usercamp_form_data_customize_panel' ); ?>

</div>