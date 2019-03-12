<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="memberlists_role_data" class="panel usercamp_options_panel">

	<div class="options_group">
		<?php
		$opts = array( 'publish_uc_memberlists', 'edit_uc_memberlists', 'delete_uc_memberlists' );
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

	<?php do_action( 'usercamp_role_data_memberlists_panel' ); ?>

</div>