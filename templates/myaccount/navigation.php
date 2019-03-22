<?php
/**
 * My Account navigation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'usercamp_before_account_navigation' );
?>

<nav class="usercamp-account-navigation">
	<ul>
		<?php foreach ( uc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo uc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( uc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'usercamp_after_account_navigation' ); ?>
