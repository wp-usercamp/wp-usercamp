<?php
/**
 * Message Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the count of notices added, either for all notices (default) or for one.
 */
function uc_notice_count( $notice_type = '' ) {

	$notice_count = 0;
	$all_notices  = (array) get_option( 'uc_notices' );

	if ( isset( $all_notices[ $notice_type ] ) ) {

		$notice_count = count( $all_notices[ $notice_type ] );

	} elseif ( empty( $notice_type ) ) {

		foreach ( $all_notices as $notices ) {
			$notice_count += count( $notices );
		}
	}

	return $notice_count;
}

/**
 * Check if a notice has already been added.
 */
function uc_has_notice( $message, $notice_type = 'success' ) {

	$notices = (array) get_option( 'uc_notices' );
	$notices = isset( $notices[ $notice_type ] ) ? $notices[ $notice_type ] : array();
	return array_search( $message, $notices, true ) !== false;
}

/**
 * Add and store a notice.
 */
function uc_add_notice( $message, $notice_type = 'success' ) {

	$notices = (array) get_option( 'uc_notices' );

	// Backward compatibility.
	if ( 'success' === $notice_type ) {
		$message = apply_filters( 'usercamp_add_message', $message );
	}

	$notices[ $notice_type ][] = apply_filters( 'usercamp_add_' . $notice_type, $message );

	update_option( 'uc_notices', $notices );
}

/**
 * Set all notices at once.
 */
function uc_set_notices( $notices ) {
	update_option( 'uc_notices', $notices );
}


/**
 * Unset all notices.
 */
function uc_clear_notices() {
	update_option( 'uc_notices', null );
}

/**
 * Prints messages and errors which are stored in the session, then clears them.
 */
function uc_print_notices( $return = false ) {

	$all_notices  = (array) get_option( 'uc_notices' );
	$notice_types = apply_filters( 'usercamp_notice_types', array( 'error', 'success', 'notice' ) );

	// Buffer output.
	ob_start();

	foreach ( $notice_types as $notice_type ) {
		if ( uc_notice_count( $notice_type ) > 0 ) {
			uc_get_template( "notices/{$notice_type}.php", array(
				'messages' => array_filter( $all_notices[ $notice_type ] ),
			) );
		}
	}

	uc_clear_notices();

	$notices = uc_kses_notice( ob_get_clean() );

	if ( $return ) {
		return $notices;
	}

	echo $notices;
}

/**
 * Print a single notice immediately.
 */
function uc_print_notice( $message, $notice_type = 'success' ) {
	if ( 'success' === $notice_type ) {
		$message = apply_filters( 'usercamp_add_message', $message );
	}

	uc_get_template( "notices/{$notice_type}.php", array(
		'messages' => array( apply_filters( 'usercamp_add_' . $notice_type, $message ) ),
	) );
}

/**
 * Returns all queued notices, optionally filtered by a notice type.
 */
function uc_get_notices( $notice_type = '' ) {

	$all_notices = (array) get_option( 'uc_notices' );

	if ( empty( $notice_type ) ) {
		$notices = $all_notices;
	} elseif ( isset( $all_notices[ $notice_type ] ) ) {
		$notices = $all_notices[ $notice_type ];
	} else {
		$notices = array();
	}

	return $notices;
}

/**
 * Add notices for WP Errors.
 */
function uc_add_wp_error_notices( $errors ) {
	if ( is_wp_error( $errors ) && $errors->get_error_messages() ) {
		foreach ( $errors->get_error_messages() as $error ) {
			uc_add_notice( $error, 'error' );
		}
	}
}

/**
 * Filters out the same tags as wp_kses_post, but allows tabindex for <a> element.
 */
function uc_kses_notice( $message ) {
	return wp_kses( $message,
		array_replace_recursive(
			wp_kses_allowed_html( 'post' ),
			array(
				'a' => array(
					'tabindex' => true,
				),
			)
		)
	);
}