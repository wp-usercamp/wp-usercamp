<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="admin_role_caps" class="panel usercamp_options_panel">

	<div class="options_group">
		<?php
		$opts = array( 'uc_edit_users', 'uc_delete_users', 'uc_alter_users' );
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
		$opts = array( 'uc_access_wpadmin' );
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
		$opts = array( 'uc_settings', 'uc_addons', 'manage_usercamp' );
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

	<?php do_action( 'usercamp_role_caps_admin_panel' ); ?>

</div>