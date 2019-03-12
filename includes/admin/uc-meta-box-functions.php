<?php
/**
 * Meta Box Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output a switch box.
 */
function usercamp_wp_switch( $field ) {
	global $thepostid, $post;

	$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
	$field['class']         = isset( $field['class'] ) ? $field['class'] : 'checkbox';
	$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
	$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : 'toggle_wrap';
	$field['value']         = isset( $field['value'] ) ? (bool) $field['value'] : get_post_meta( $thepostid, $field['id'], true );
	$field['cbvalue']       = isset( $field['cbvalue'] ) ? $field['cbvalue'] : 'yes';
	$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;

	// Custom attribute handling
	$custom_attributes = array();

	if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

		foreach ( $field['custom_attributes'] as $attribute => $value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
		}
	}

	echo '<fieldset class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '" data-type="switch">
		<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

	echo '<input type="checkbox" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['cbvalue'] ) . '" ' . checked( $field['value'], $field['cbvalue'], false ) . '  ' . implode( ' ', $custom_attributes ) . '/> ';
	echo '<div class="uc-toggle" data-toggle-on="' . $field['value'] . '"></div>';

	if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
		echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
	}

	if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		echo uc_help_tip( $field['description'] );
	}

	echo '</fieldset>';
}

/**
 * Output a text input box.
 */
function usercamp_wp_text_input( $field ) {
	global $thepostid, $post;

	$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
	$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	$field['class']         = isset( $field['class'] ) ? $field['class'] : 'short';
	$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
	$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
	$field['value']         = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );
	$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['type']          = isset( $field['type'] ) ? $field['type'] : 'text';
	$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;
	$data_type              = empty( $field['data_type'] ) ? '' : $field['data_type'];

	switch ( $data_type ) {
		default:
			break;
	}

	// Custom attribute handling
	$custom_attributes = array();

	if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

		foreach ( $field['custom_attributes'] as $attribute => $value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
		}
	}

	echo '<fieldset class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '" data-type="text">
		<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

	echo '<input type="' . esc_attr( $field['type'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" ' . implode( ' ', $custom_attributes ) . ' /> ';

	if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
		echo '<div class="clear"></div><span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
	}

	if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		echo uc_help_tip( $field['description'] );
	}

	echo '</fieldset>';
}

/**
 * Output a select input box.
 */
function usercamp_wp_select( $field ) {
	global $thepostid, $post;

	$thepostid = empty( $thepostid ) ? $post->ID : $thepostid;
	$field     = wp_parse_args(
		$field, array(
			'class'             => 'uc-select short',
			'style'             => '',
			'wrapper_class'     => '',
			'value'             => get_post_meta( $thepostid, $field['id'], true ),
			'name'              => $field['id'],
			'placeholder'		=> '',
			'desc_tip'          => false,
			'custom_attributes' => array(),
		)
	);

	$wrapper_attributes = array(
		'class' => $field['wrapper_class'] . " form-field {$field['id']}_field",
	);

	$label_attributes = array(
		'for' => $field['id'],
	);

	$field_attributes          			= (array) $field['custom_attributes'];
	$field_attributes['style'] 			= $field['style'];
	$field_attributes['id']    			= $field['id'];
	$field_attributes['name']  			= $field['name'];
	$field_attributes['placeholder'] 	= $field['placeholder'];
	$field_attributes['class'] 			= $field['class'];

	// Multi.
	if ( isset( $field_attributes['multiple'] ) ) {
		$field_attributes['class'] 	= 'uc-select-multi short';
		$field_attributes['name'] 	= $field['name'] . '[]';
	}

	$tooltip     = ! empty( $field['description'] ) && false !== $field['desc_tip'] ? $field['description'] : '';
	$description = ! empty( $field['description'] ) && false === $field['desc_tip'] ? $field['description'] : '';

	if ( $field['value'] && is_array( $field['value'] ) ) {
		$field['options'] = array_replace( array_flip( $field['value'] ), $field['options'] );
	}
	?>
	<fieldset <?php echo uc_implode_html_attributes( $wrapper_attributes ); ?> data-type="select">
		<label <?php echo uc_implode_html_attributes( $label_attributes ); ?>><?php echo wp_kses_post( $field['label'] ); ?></label>
		<select <?php echo uc_implode_html_attributes( $field_attributes ); ?>>
			<?php
			foreach ( $field['options'] as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '"' . uc_selected( $key, $field['value'] ) . '>' . esc_html( $value ) . '</option>';
			}
			?>
		</select>
		<?php if ( $description ) : ?>
			<div class="clear"></div><span class="description"><?php echo wp_kses_post( $description ); ?></span>
		<?php endif; ?>
		<?php if ( $tooltip ) : ?>
			<?php echo uc_help_tip( $tooltip ); ?>
		<?php endif; ?>
	</fieldset>
	<?php
}

/**
 * Output a textarea input box.
 */
function usercamp_wp_textarea( $field ) {
	global $thepostid, $post;

	$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
	$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	$field['class']         = isset( $field['class'] ) ? $field['class'] : 'short';
	$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
	$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
	$field['value']         = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );
	$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;
	$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['rows']          = isset( $field['rows'] ) ? $field['rows'] : 2;
	$field['cols']          = isset( $field['cols'] ) ? $field['cols'] : 20;

	// Custom attribute handling
	$custom_attributes = array();

	if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

		foreach ( $field['custom_attributes'] as $attribute => $value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
		}
	}

	echo '<fieldset class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '" data-type="textarea">
		<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

	echo '<textarea class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '"  name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" rows="' . esc_attr( $field['rows'] ) . '" cols="' . esc_attr( $field['cols'] ) . '" ' . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $field['value'] ) . '</textarea> ';

	if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
		echo '<div class="clear"></div><span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
	}

	if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		echo uc_help_tip( $field['description'] );
	}

	echo '</fieldset>';
}

/**
 * Output drag-n-drop tool bar.
 */
function uc_dragdrop_topbar() {
	global $the_form;
	?>
	<div class="uc-bld-topbar">
		<div class="uc-bld-left"><span class="description"></span></div>
		<div class="uc-bld-right">
			<a href="#" class="button button-primary disabled save_form" data-id="<?php echo absint( $the_form->id ); ?>"><i data-feather="upload-cloud"></i><span><?php echo __( 'Save changes', 'usercamp' ); ?></span></a>
		</div><div class="clear"></div>
	</div>
	<?php
}

/**
 * Output drag-n-drop default row.
 */
function uc_dragdrop_default() {
	global $the_form;
	?>
	<div class="uc-bld-row hidden">
		<?php uc_dragdrop_row_head(); ?>
		<?php uc_dragdrop_grid(); ?>
		<div class="uc-bld-cols">
			<div class="uc-bld-col grid-100">
				<div class="uc-bld-elems">
					<div class="uc-bld-elem hidden">
						<div class="uc-bld-left">
							<span class="uc-bld-action"><a href="#" class="uc-move-field"><i data-feather="move"></i></a></span>
							<span class="uc-bld-icon">{icon}</span>
							<span class="uc-bld-label">{label}</span>
							<span class="uc-bld-helper">{key}</span>
						</div>
						<?php uc_dragdrop_field_actions(); ?>
					</div>
				</div>
				<?php uc_dragdrop_add(); ?>
			</div>
		</div>

	</div>
	<?php
}

/**
 * Output drag-n-drop rows loop.
 */
function uc_dragdrop_rows() {
	global $the_form;
	?>
	<?php if ( $the_form->row_count > 0 ) : ?>
	<?php for( $i = 1; $i <= $the_form->row_count; $i++ ) : ?>

	<div class="uc-bld-row">
		<?php uc_dragdrop_row_head(); ?>
		<?php uc_dragdrop_grid( $i ); ?>
		<?php uc_dragdrop_columns( $i ); ?>
	</div>

	<?php endfor; ?>
	<?php endif; ?>
	<?php
}

/**
 * Output drag-n-drop columns loop.
 */
function uc_dragdrop_columns( $i ) {
	global $the_form;
	?>
	<div class="uc-bld-cols">

	<?php for( $c = 0; $c < $the_form->cols[$i]['count']; $c++ ) : ?>

	<div class="uc-bld-col grid-<?php echo $the_form->cols[$i]['layout'][$c]; ?>">
		<div class="uc-bld-elems">
			<?php foreach( (array) $the_form->fields_in( $i, $c ) as $k => $field ) : $r = $field['data']; ?>
			<div class="uc-bld-elem" <?php echo uc_get_data_attributes( $r ); ?>>
				<div class="uc-bld-left">
					<span class="uc-bld-action"><a href="#" class="uc-move-field"><i data-feather="move"></i></a></span>
					<span class="uc-bld-icon"><i data-feather="<?php echo ( $r['icon'] ) ? esc_attr( $r['icon'] ) : esc_attr( uc_get_field_type( $r['type'], 'icon' ) ); ?>"></i></span>
					<span class="uc-bld-label"><?php echo esc_html( $r['label'] ); ?></span>
					<span class="uc-bld-helper"><?php echo esc_attr( $r['key'] ); ?></span>
				</div>
				<?php uc_dragdrop_field_actions(); ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php uc_dragdrop_add(); ?>
	</div>

	<?php endfor; ?>

	</div>
	<?php
}

/**
 * Output drag-n-drop grid options.
 */
function uc_dragdrop_grid( $i = '' ) {
	global $the_form;
	?>
	<div class="uc-bld-bar">
		<?php foreach( (array) uc_get_form_grid() as $key => $grid ) {
			if ( $i && ( $grid['spans'] == $the_form->cols[$i]['layout'] ) ) {
				$active = 'active';
			} else if ( $i && $the_form->cols[$i]['layout'][0] == 100 && ( $grid['spans'][0] == $the_form->cols[$i]['layout'][0] ) ) {
				$active = 'active';
			} else {
				$active = '';
			}
			?>
			<a href="#" class="grid grid_<?php echo $key; ?> <?php echo $active; ?>" data-layout="<?php echo implode(':', $grid['spans'] ); ?>">
				<?php foreach( $grid['spans'] as $span ) { ?>
				<span class="grid_<?php echo $span; ?>"><i data-feather="align-justify"></i></span>
				<?php } ?>
			</a>
		<?php } ?>
	</div><div class="clear"></div>
	<?php
}

/**
 * Output drag-n-drop add row.
 */
function uc_dragdrop_addrow() {
	global $the_form;
	?>
	<div class="uc-bld-new"><a href="#" class="uc-add-row"><i data-feather="plus"></i></a></div>
	<?php
}

/**
 * Output drag-n-drop add element.
 */
function uc_dragdrop_add() {
	global $the_form;
	?>
	<div class="uc-bld-add"><a href="#uc-add-element" class="uc-add-element" rel="modal:open"><i data-feather="plus"></i></a></div>
	<?php
}

/**
 * Output drag-n-drop row header.
 */
function uc_dragdrop_row_head() {
	global $the_form;
	?>
	<div class="uc-bld-elem">
		<div class="uc-bld-left">
			<span class="uc-bld-action"><a href="#" class="uc-move-row"><i data-feather="move"></i></a></span>
			<span class="uc-bld-icon"><a href="#" class="uc-toggle-row"><i data-feather="chevron-up"></i></a></span>
			<span class="uc-bld-label"><?php echo __( 'Untitled Row', 'usercamp' ); ?></span>
		</div>
		<div class="uc-bld-right">
			<span class="uc-bld-action"><a href="#" class="uc-edit-row"><i data-feather="edit-2"></i></a></span>
			<span class="uc-bld-action"><a href="#" class="uc-duplicate-row"><i data-feather="copy"></i></a></span>
			<span class="uc-bld-action"><a href="#" class="uc-delete-row"><i data-feather="trash-2"></i></a></span>
		</div><div class="clear"></div>
	</div>
	<?php
}

/**
 * Output drag-n-drop field actions.
 */
function uc_dragdrop_field_actions() {
	global $the_form;
	?>
	<div class="uc-bld-right">
		<span class="uc-bld-action"><a href="#" class="uc-edit-field"><i data-feather="edit-2"></i></a></span>
		<span class="uc-bld-action"><a href="#" class="uc-duplicate-field"><i data-feather="copy"></i></a></span>
		<span class="uc-bld-action"><a href="#" class="uc-delete-field"><i data-feather="trash-2"></i></a></span>
	</div>
	<div class="clear"></div>
	<?php
}