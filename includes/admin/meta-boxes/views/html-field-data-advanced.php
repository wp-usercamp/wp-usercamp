<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="advanced_field_data" class="panel usercamp_options_panel hidden">

	<div class="options_group">
		<?php
		usercamp_wp_textarea(
			array(
				'id'          		=> 'error_hooks',
				'value'       		=> $the_field->error_hooks,
				'label'       		=> __( 'Error validation hooks', 'usercamp' ),
				'rows'				=> 3,
			)
		);

		usercamp_wp_textarea(
			array(
				'id'          		=> 'display_hooks',
				'value'       		=> $the_field->display_hooks,
				'label'       		=> __( 'Field output hooks', 'usercamp' ),
				'rows'				=> 3,
			)
		);

		usercamp_wp_textarea(
			array(
				'id'          		=> 'filter_hooks',
				'value'       		=> $the_field->filter_hooks,
				'label'       		=> __( 'Pre save filter hooks', 'usercamp' ),
				'rows'				=> 3,
			)
		);

		usercamp_wp_textarea(
			array(
				'id'          		=> 'presave_hooks',
				'value'       		=> $the_field->presave_hooks,
				'label'       		=> __( 'Pre save action hooks', 'usercamp' ),
				'rows'				=> 3,
			)
		);

		usercamp_wp_textarea(
			array(
				'id'          		=> 'postsave_hooks',
				'value'       		=> $the_field->postsave_hooks,
				'label'       		=> __( 'Post save action hooks', 'usercamp' ),
				'rows'				=> 3,
			)
		);
		?>
	</div>

	<?php do_action( 'usercamp_field_data_advanced_panel' ); ?>

</div>