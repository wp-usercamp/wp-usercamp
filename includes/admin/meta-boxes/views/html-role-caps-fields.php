<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="fields_role_caps" class="panel usercamp_options_panel hidden">

	<div class="options_group">
		<?php
		$opts = array( 'publish_uc_fields', 'edit_uc_fields', 'delete_uc_fields' );
		foreach( $opts as $opt ) {
			usercamp_wp_switch(
				array(
					'id'        	=> $opt,
					'label'			=> uc_get_cap_title( $opt ),
					'value'			=> $the_role->get_cap( $opt ),
					'cbvalue'		=> 1
				)
			);
		}
		?>
	</div>

	<?php do_action( 'usercamp_role_caps_fields_panel' ); ?>

</div>