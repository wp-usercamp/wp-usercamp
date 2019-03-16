<?php
/**
 * Form instances metabox.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<ul class="uc_list">

	<?php if ( empty( $the_form->get_instances() ) ) : ?>
	<li>

		<?php _e( 'This form has not been integrated to any of the pages yet.', 'usercamp' ); ?>

	</li>
	<?php endif; ?>

	<?php foreach( $the_form->get_instances() as $instance ) : ?>
	<li>

		<a href="<?php echo get_permalink( $instance['ID'] ); ?>">
			<?php echo esc_html( $instance['post_title'] ); ?>
		</a>

		<a href="<?php echo get_edit_post_link( $instance['ID'] ); ?>" class="uc-tag-icon">
			<?php _e( 'Edit', 'usercamp' ); ?>
		</a>

		<a href="<?php echo get_permalink( $instance['ID'] ); ?>" class="uc-tag-icon">
			<?php _e( 'View', 'usercamp' ); ?>
		</a>

	</li>
	<?php endforeach; ?>

</ul>