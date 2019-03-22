<?php
/**
 * My Account page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * My Account navigation.
 */
do_action( 'usercamp_account_navigation' ); ?>

<div class="usercamp-myaccount-content">
	<?php
		/**
		 * My Account content.
		 */
		do_action( 'usercamp_account_content' );
	?>
</div>
