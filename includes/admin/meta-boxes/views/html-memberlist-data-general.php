<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="general_memberlist_data" class="panel usercamp_options_panel">

	<div class="options_group">
		<?php
		usercamp_wp_switch(
			array(
				'id'        		=> 'use_ajax',
				'label'				=> __( 'Show results with ajax', 'usercamp' ),
				'value'				=> $the_memberlist->use_ajax ? $the_memberlist->use_ajax : 'yes',
				'description'		=> __( 'If enabled, new results will be displayed without refreshing the page.', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);

		usercamp_wp_text_input(
			array(
				'id'          		=> 'per_page',
				'value'       		=> $the_memberlist->per_page,
				'type'				=> 'number',
				'label'       		=> __( 'Number of users per page', 'usercamp' ),
				'description'		=> __( 'How many users should be displayed per page.', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);

		usercamp_wp_text_input(
			array(
				'id'          		=> 'per_row',
				'value'       		=> $the_memberlist->per_row,
				'type'				=> 'number',
				'label'       		=> __( 'Number of users per row', 'usercamp' ),
				'description'		=> __( 'How many users should be displayed per each row.', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);

		usercamp_wp_switch(
			array(
				'id'        		=> 'login_required',
				'label'				=> __( 'Require users to login to view', 'usercamp' ),
				'value'				=> $the_memberlist->login_required,
				'cbvalue'			=> 1,
				'description'		=> __( 'If enabled, non-logged in users will not be able to view this member list.', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);
		?>
	</div>

	<div class="options_group">
		<?php
		usercamp_wp_select(
			array(
				'id'          		=> 'roles',
				'value'       		=> $the_memberlist->roles,
				'label'       		=> __( 'Who to show in this list?', 'usercamp' ),
				'options'     		=> array_merge( array( '_all' => __( 'Everyone excluding admins', 'usercamp' ) ), usercamp_get_roles() ),
				'placeholder'		=> __( 'Everyone', 'usercamp' ),
				'description' 		=> __( 'This controls which user groups can be shown in this specific list.', 'usercamp' ),
				'desc_tip'			=> true,
				'custom_attributes' => array( 'multiple' => 'multiple' ),
			)
		);

		usercamp_wp_rule(
			array(
				'id'          		=> 'rules',
				'value'       		=> $the_memberlist->rules,
				'label'       		=> __( 'Only show users with specific metadata', 'usercamp' ),
			)
		);
		?>
	</div>

	<?php do_action( 'usercamp_memberlist_data_general_panel' ); ?>

</div>