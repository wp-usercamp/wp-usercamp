<?php
/**
 * My Profile header
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'usercamp_before_profile_header' );
?>

<div class="usercamp-profile-header">

	<div class="usercamp-profile-cover <?php echo uc_get_profile_cover_classes(); ?>">

	</div>

</div>

<div class="usercamp-profile-nav">

	<div class="usercamp-profile-photo">
		<a href="#"><img src="https://pbs.twimg.com/profile_images/595659104384905218/bOtXKmdP_400x400.jpg" alt="" /></a>
	</div>

	<div class="usercamp-profile-about">

		<div class="usercamp-profile-user">
			<div class="usercamp-profile-name">
				<a href="#"><?php echo esc_attr( $the_user->get( 'display_name' ) ); ?></a>
			</div>
			<div class="usercamp-profile-hash">
				<a href="#"><span>@<b><?php echo esc_attr( $the_user->get( 'user_login' ) ); ?></b></span></a>
			</div>
		</div>
		
		<div class="usercamp-profile-bio">The world's easiest way to create online communities with WordPress.</div>

		<div class="usercamp-profile-meta">
			<div class="usercamp-profile-metadata"><?php echo uc_svg_icon( 'map-pin' ); ?><span>Michigan, USA</span></div>
			<div class="usercamp-profile-metadata"><?php echo uc_svg_icon( 'gift' ); ?><span>Born on March, 1979</span></div>
			<div class="usercamp-profile-metadata"><?php echo uc_svg_icon( 'calendar' ); ?><span>Joined </span></div>
		</div>

	</div>

</div>

<?php do_action( 'usercamp_after_profile_header' ); ?>