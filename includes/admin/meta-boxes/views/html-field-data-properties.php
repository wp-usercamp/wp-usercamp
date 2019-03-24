<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="properties_field_data" class="panel usercamp_options_panel hidden">

	<div class="options_group">
		<?php
		usercamp_wp_switch(
			array(
				'id'        		=> 'is_required',
				'label'				=> __( 'Make this field required?', 'usercamp' ),
				'value'				=> $the_field->is_required,
				'cbvalue'			=> 1,
			)
		);

		usercamp_wp_switch(
			array(
				'id'        		=> 'is_private',
				'label'				=> __( 'Private', 'usercamp' ),
				'value'				=> $the_field->is_private,
				'cbvalue'			=> 1,
				'description'		=> __( 'This will hide the field info from public without permission. e.g. to hide e-mail', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);

		usercamp_wp_switch(
			array(
				'id'        		=> 'is_readonly',
				'label'				=> __( 'Read Only', 'usercamp' ),
				'value'				=> $the_field->is_readonly,
				'cbvalue'			=> 1,
				'description'		=> __( 'Enable to prevent the user from editing this custom field', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);
		?>
	</div>

	<div class="options_group">
		<?php
		usercamp_wp_select(
			array(
				'id'          		=> 'can_view',
				'value'       		=> $the_field->can_view,
				'label'       		=> __( 'Who can view?', 'usercamp' ),
				'options'     		=> array_merge( array( '_none' => __( 'No one', 'usercamp' ), 'owner' => __( 'Owner', 'usercamp' ) ), usercamp_get_roles() ),
				'placeholder'		=> __( 'Everyone', 'usercamp' ),
				'description' 		=> __( 'Who can view this custom field.', 'usercamp' ),
				'desc_tip'			=> true,
				'custom_attributes' => array( 'multiple' => 'multiple' ),
			)
		);
		?>
	</div>

	<?php do_action( 'usercamp_field_data_properties_panel' ); ?>

</div>