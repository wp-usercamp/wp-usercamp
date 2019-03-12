<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="general_role_data" class="panel usercamp_options_panel">

	<div class="options_group">
		<?php
		$opts = array( 'uc_edit_profile' );
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

	<div class="options_group">
		<?php
		$opts = array( 'uc_view_profiles', 'uc_view_memberlist', 'uc_search_memberlist' );
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

	<div class="options_group">
		<?php
		$opts = array( 'uc_view_private', 'uc_view_private_data' );
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

	<?php do_action( 'usercamp_role_data_general_panel' ); ?>

</div>