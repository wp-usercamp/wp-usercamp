<?php
/**
 * File Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Convert bytes to round megabytes.
 */
function uc_bytes_to_mb( $bytes ) { 
	return round( $bytes / 1048576 ); 
}

/**
 * Get crop ratios.
 */
function uc_get_crop_ratios() {
	return apply_filters( 'uc_get_crop_ratios', array(
		'square'		=> __( 'Square (1:1)', 'usercamp' ),
		'portrait'		=> __( 'Portrait (5:4)', 'usercamp' ),
		'landscape'		=> __( 'Landscape (16:9)', 'usercamp' ),
	) );
}