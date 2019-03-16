<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="general_field_data" class="panel usercamp_options_panel">

	<div class="options_group">
		<?php
		usercamp_wp_text_input(
			array(
				'id'          		=> 'key',
				'value'       		=> $the_field->key,
				'label'       		=> __( 'Unique key', 'usercamp' ),
				'placeholder' 		=> _x( 'custom_field', 'placeholder', 'usercamp' ),
				'description' 		=> __( 'This is how the custom field will be identified in the system.', 'usercamp' ),
			)
		);

		usercamp_wp_select(
			array(
				'id'          		=> 'type',
				'value'       		=> $the_field->type,
				'label'       		=> __( 'Custom field type', 'usercamp' ),
				'options'     		=> array_combine( array_keys( usercamp_get_field_types() ), array_column( usercamp_get_field_types(), 'label' ) ),
				'description' 		=> __( 'Controls how this custom field should be filled by the user', 'usercamp' ),
				'desc_tip'			=> true,
			)
		);

		// Email
		usercamp_wp_textarea(
			array(
				'id'          		=> 'blocked_emails',
				'value'       		=> $the_field->blocked_emails,
				'label'       		=> __( 'Blocked emails', 'usercamp' ),
				'placeholder' 		=> 'you@domain.com' . "\n" . '*@domain.com' . "\n" . 'domain.com',
				'rows'				=> 5,
				'description'		=> __( 'Use this option if you want to block specific domains/emails from being used as email.', 'usercamp' ),
				'wrapper_class' 	=> 'show_if_type_eq_email hidden',
			)
		);

		usercamp_wp_textarea(
			array(
				'id'          		=> 'allowed_emails',
				'value'       		=> $the_field->allowed_emails,
				'label'       		=> __( 'Allowed emails', 'usercamp' ),
				'placeholder' 		=> 'you@domain.com' . "\n" . '*@domain.com' . "\n" . 'domain.com',
				'rows'				=> 5,
				'description'		=> __( 'Use this option if you want to allow specific domains/emails only to be used as email.', 'usercamp' ),
				'wrapper_class' 	=> 'show_if_type_eq_email hidden',
			)
		);

		// Select
		usercamp_wp_textarea(
			array(
				'id'          		=> 'dropdown_options',
				'value'       		=> $the_field->dropdown_options,
				'label'       		=> __( 'Dropdown options list', 'usercamp' ),
				'placeholder' 		=> __( 'Please enter one option per line.', 'usercamp' ),
				'rows'				=> 5,
				'wrapper_class' 	=> 'show_if_type_eq_select hidden',
			)
		);

		// Checkbox
		usercamp_wp_textarea(
			array(
				'id'          		=> 'checkbox_options',
				'value'       		=> $the_field->checkbox_options,
				'label'       		=> __( 'Checkbox options list', 'usercamp' ),
				'placeholder' 		=> __( 'Please enter one option per line.', 'usercamp' ),
				'rows'				=> 5,
				'wrapper_class' 	=> 'show_if_type_eq_checkbox hidden',
			)
		);

		// Radio
		usercamp_wp_textarea(
			array(
				'id'          		=> 'radio_options',
				'value'       		=> $the_field->radio_options,
				'label'       		=> __( 'Radio options list', 'usercamp' ),
				'placeholder' 		=> __( 'Please enter one option per line.', 'usercamp' ),
				'rows'				=> 5,
				'wrapper_class' 	=> 'show_if_type_eq_radio hidden',
			)
		);

		// Image
		usercamp_wp_switch(
			array(
				'id'        		=> 'is_crop',
				'label'				=> __( 'Enable crop feature', 'usercamp' ),
				'value'				=> $the_field->is_crop,
				'cbvalue'			=> 1,
				'description'		=> __( 'If enabled, user will be able to crop the uploaded image', 'usercamp' ),
				'desc_tip'			=> true,
				'wrapper_class' 	=> 'show_if_type_eq_image hidden',
			)
		);

		usercamp_wp_select(
			array(
				'id'          		=> 'crop_ratio',
				'value'       		=> $the_field->crop_ratio,
				'label'       		=> __( 'Crop ratio', 'usercamp' ),
				'options'     		=> uc_get_crop_ratios(),
				'description' 		=> __( 'Control the crop ratio for this image upload', 'usercamp' ),
				'desc_tip'			=> true,
				'wrapper_class' 	=> 'show_if_type_eq_image show_if_is_crop_eq_yes hidden',
			)
		);

		usercamp_wp_switch(
			array(
				'id'        		=> 'vertical_crop',
				'label'				=> __( 'Vertical cropping?', 'usercamp' ),
				'value'				=> $the_field->vertical_crop,
				'cbvalue'			=> 1,
				'description'		=> __( 'If enabled, the image will be cropped vertically instead of horizontally', 'usercamp' ),
				'desc_tip'			=> true,
				'wrapper_class' 	=> 'show_if_type_eq_image show_if_is_crop_eq_yes hidden',
			)
		);

		usercamp_wp_text_input(
			array(
				'id'          		=> 'max_image_size',
				'value'       		=> $the_field->max_image_size,
				'label'       		=> __( 'Max upload size (MB)', 'usercamp' ),
				'description' 		=> sprintf( __( 'Leave blank to use system default (Maximum allowed size: %dMB)', 'usercamp' ), uc_bytes_to_mb( wp_max_upload_size() ) ),
				'wrapper_class' 	=> 'show_if_type_eq_image hidden',
			)
		);

		// File
		usercamp_wp_text_input(
			array(
				'id'          		=> 'max_file_size',
				'value'       		=> $the_field->max_file_size,
				'label'       		=> __( 'Max upload size (MB)', 'usercamp' ),
				'description' 		=> sprintf( __( 'Leave blank to use system default (Maximum allowed size: %dMB)', 'usercamp' ), uc_bytes_to_mb( wp_max_upload_size() ) ),
				'wrapper_class' 	=> 'show_if_type_eq_file hidden',
			)
		);
		?>
	</div>

	<?php do_action( 'usercamp_field_data_general_panel' ); ?>

</div>