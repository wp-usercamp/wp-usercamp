<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="access_role_data" class="panel usercamp_options_panel">

	<div class="options_group">
		<?php
		$opts = array( 'uc_log_in' );
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

	<?php do_action( 'usercamp_role_data_access_panel' ); ?>

</div>