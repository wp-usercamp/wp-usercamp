<?php
/**
 * Admin View: Setup - Welcome.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<h1><?php esc_html_e( 'Welcome to Usercamp Setup Wizard', 'usercamp' ); ?></h1>

<form method="post">

	<p><?php esc_html_e( 'This setup wizard was designed to help you get started quickly with your online community.', 'usercamp' ); ?></p>

	<h3><?php esc_html_e( 'Setup community base', 'usercamp' ); ?></h3>
	<?php
	usercamp_wp_switch(
		array(
			'id'        		=> '_forms',
			'label'				=> __( 'Default forms', 'usercamp' ),
			'value'				=> 1,
			'cbvalue'			=> 1,
			'description'		=> __( 'Setup default forms and custom fields', 'usercamp' ),
			'desc_tip'			=> true,
		)
	);

	usercamp_wp_switch(
		array(
			'id'        		=> '_fields',
			'label'				=> __( 'Default custom fields', 'usercamp' ),
			'value'				=> 1,
			'cbvalue'			=> 1,
			'description'		=> __( 'Set up default custom fields without forms', 'usercamp' ),
			'desc_tip'			=> true,
		)
	);

	usercamp_wp_switch(
		array(
			'id'        		=> '_roles',
			'label'				=> __( 'Default user roles', 'usercamp' ),
			'value'				=> 1,
			'cbvalue'			=> 1,
			'description'		=> __( 'Set up default user roles and assign their capabilities', 'usercamp' ),
			'desc_tip'			=> true,
		)
	);

	usercamp_wp_switch(
		array(
			'id'        		=> '_memberlists',
			'label'				=> __( 'Default member lists', 'usercamp' ),
			'value'				=> 1,
			'cbvalue'			=> 1,
			'description'		=> __( 'Set up a default member directory to show your users', 'usercamp' ),
			'desc_tip'			=> true,
		)
	);
	?>

	<h3><?php esc_html_e( 'Setup community pages', 'usercamp' ); ?></h3>
	<?php
	usercamp_wp_switch(
		array(
			'id'        		=> '_profile',
			'label'				=> __( 'User Profile page', 'usercamp' ),
			'value'				=> 1,
			'cbvalue'			=> 1,
			'description'		=> __( 'Set up the community user profile page', 'usercamp' ),
			'desc_tip'			=> true,
		)
	);

	usercamp_wp_switch(
		array(
			'id'        		=> '_account',
			'label'				=> __( 'My Account page', 'usercamp' ),
			'value'				=> 1,
			'cbvalue'			=> 1,
			'description'		=> __( 'Set up the community my account page', 'usercamp' ),
			'desc_tip'			=> true,
		)
	);

	usercamp_wp_switch(
		array(
			'id'        		=> '_register',
			'label'				=> __( 'Registration page', 'usercamp' ),
			'value'				=> 1,
			'cbvalue'			=> 1,
			'description'		=> __( 'Set up the community registration page', 'usercamp' ),
			'desc_tip'			=> true,
		)
	);

	usercamp_wp_switch(
		array(
			'id'        		=> '_login',
			'label'				=> __( 'Login page', 'usercamp' ),
			'value'				=> 1,
			'cbvalue'			=> 1,
			'description'		=> __( 'Set up the community login page', 'usercamp' ),
			'desc_tip'			=> true,
		)
	);

	usercamp_wp_switch(
		array(
			'id'        		=> '_lostpassword',
			'label'				=> __( 'Lost password page', 'usercamp' ),
			'value'				=> 1,
			'cbvalue'			=> 1,
			'description'		=> __( 'Set up the community lost password page', 'usercamp' ),
			'desc_tip'			=> true,
		)
	);
	?>

	<?php wp_nonce_field( 'uc-setup' ); ?>

	<p class="uc-setup-actions step">
		<button type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Let&lsquo;s go!', 'usercamp' ); ?>" name="save_step"><?php esc_html_e( 'Let&lsquo;s go!', 'usercamp' ); ?></button>
	</p>

</form>