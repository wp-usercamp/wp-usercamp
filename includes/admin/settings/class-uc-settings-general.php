<?php
/**
 * General Settings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'UC_Settings_General', false ) ) {
	return new UC_Settings_General();
}

/**
 * UC_Settings_General class.
 */
class UC_Settings_General extends UC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = __( 'General', 'usercamp' );

		parent::__construct();
	}

	/**
	 * Get settings array.
	 */
	public function get_settings() {

		$settings = apply_filters(
			'usercamp_general_settings', array(

				array(
					'title' => __( 'Store Address', 'usercamp' ),
					'type'  => 'title',
					'desc'  => __( 'This is where your business is located. Tax rates and shipping rates will use this address.', 'usercamp' ),
					'id'    => 'store_address',
				),

				array(
					'type' => 'sectionend',
					'id'   => 'store_address',
				),

			)
		);

		return apply_filters( 'usercamp_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output a color picker input box.
	 */
	public function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="color_box">' . wc_help_tip( $desc ) . '
			<input name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
		</div>';
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		$settings = $this->get_settings();

		UC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		UC_Admin_Settings::save_fields( $settings );
	}
}

return new UC_Settings_General();