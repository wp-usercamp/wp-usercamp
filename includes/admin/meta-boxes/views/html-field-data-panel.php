<?php
/**
 * Custom field data metabox.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="panel-wrap field_data">

	<ul class="field_data_tabs uc-tabs">
		<?php foreach ( self::get_tabs() as $key => $tab ) : ?>
			<li class="<?php echo esc_attr( $key ); ?>_options <?php echo esc_attr( $key ); ?>_tab <?php echo esc_attr( isset( $tab['class'] ) ? implode( ' ', (array) $tab['class'] ) : '' ); ?>">
				<a href="#<?php echo esc_attr( $tab['target'] ); ?>"><i data-feather="<?php echo esc_html( $tab['icon'] ); ?>"></i><span><?php echo esc_html( $tab['label'] ); ?></span></a>
			</li>
		<?php endforeach; ?>
		<?php do_action( 'usercamp_field_write_panel_tabs' ); ?>
	</ul>

	<?php
		self::output_tabs();
		do_action( 'usercamp_field_data_panels' );
	?>
	<div class="clear"></div>
</div>