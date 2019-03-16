<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="general_form_data" class="panel usercamp_options_panel">

	<div class="options_group">
		<?php
		usercamp_wp_switch(
			array(
				'id'        		=> 'use_ajax',
				'label'				=> __( 'Use ajax for submitting', 'usercamp' ),
				'value'				=> $the_form->use_ajax,
				'description'		=> __( 'If enabled, this form will be submitted / sent through ajax.', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);
		?>
	</div>

	<?php do_action( 'usercamp_form_data_general_panel' ); ?>

</div>