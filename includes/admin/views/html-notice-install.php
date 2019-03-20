<?php
/**
 * Admin View: Notice - Install.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="message" class="updated usercamp-message">

	<p><?php _e( '<strong>Welcome to Usercamp</strong> &#8211; You&lsquo;re almost ready to start your online community :)', 'usercamp' ); ?></p>

	<p class="submit">

		<a href="<?php echo esc_url( admin_url( 'admin.php?page=uc-setup' ) ); ?>" class="button-primary"><?php _e( 'Run the Setup Wizard', 'usercamp' ); ?></a> 

		<a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'uc-hide-notice', 'install' ), 'usercamp_hide_notices_nonce', '_uc_notice_nonce' ) ); ?>">
			<?php _e( 'Skip setup', 'usercamp' ); ?>
		</a>

	</p>

</div>