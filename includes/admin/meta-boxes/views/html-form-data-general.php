<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="general_form_data" class="panel usercamp_options_panel">

	<div class="options_group">
		<?php
		usercamp_wp_select(
			array(
				'id'          		=> '_type',
				'value'       		=> $the_form->type,
				'label'       		=> __( 'Form type', 'usercamp' ),
				'options'     		=> array_combine( array_keys( usercamp_get_form_types() ), array_column( usercamp_get_form_types(), 'label' ) ),
				'description' 		=> __( 'Choose a type for this form.', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);

		usercamp_wp_switch(
			array(
				'id'        		=> 'use_ajax',
				'label'				=> __( 'Use AJAX', 'usercamp' ),
				'value'				=> $the_form->use_ajax,
				'description'		=> __( 'If enabled, this form will interact instantly without page refresh.', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);

		usercamp_wp_select(
			array(
				'id'          		=> 'icons',
				'value'       		=> $the_form->icons,
				'label'       		=> __( 'Show field icons', 'usercamp' ),
				'options'     		=> array(
					'hide'		=> __( 'Do not show', 'usercamp' ),
					'label'		=> __( 'Beside field label', 'usercamp' ),
					'inside'	=> __( 'Inside field (Which support text input)', 'usercamp' ),
				),
				'description' 		=> __( 'If enabled, field icons will be displayed in the form.', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);
		?>
	</div>

	<?php do_action( 'usercamp_form_data_general_panel' ); ?>

</div>