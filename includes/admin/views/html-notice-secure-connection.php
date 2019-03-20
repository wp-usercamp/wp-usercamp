<?php
/**
 * Admin View: Notice - Secure connection.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="message" class="updated usercamp-message">

	<a class="usercamp-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'uc-hide-notice', 'no_secure_connection' ), 'usercamp_hide_notices_nonce', '_uc_notice_nonce' ) ); ?>">
		<?php esc_html_e( 'Dismiss', 'usercamp' ); ?>
	</a>

	<p>
	<?php
		echo wp_kses_post( sprintf(
			__( 'Your website does not appear to be using a secure connection. We highly recommend serving your entire website over an HTTPS connection to help keep customer data secure. <a href="%s">Learn more.</a>', 'usercamp' ),
			'https://docs.usercamp.io'
		) );
	?>
	</p>

</div>