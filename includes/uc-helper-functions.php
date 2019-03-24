<?php
/**
 * Helper Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Array insert before a key.
 */
function uc_array_insert_before( $key, array &$array, $new_key, $new_value ) {
	if ( array_key_exists( $key, $array ) ) {
		$new = array();
		foreach ( $array as $k => $value ) {
			if ( $k === $key ) {
				$new[$new_key] = $new_value;
			}
			$new[$k] = $value;
		}
		return $new;
	}
	return false;
}

/**
 * Array insert after a key.
 */
function uc_array_insert_after( $key, array &$array, $new_key, $new_value ) {
	if ( array_key_exists( $key, $array ) ) {
		$new = array();
		foreach ( $array as $k => $value ) {
			$new[$k] = $value;
			if ( $k === $key ) {
				$new[$new_key] = $new_value;
			}
		}
		return $new;
	}
	return false;
}

/**
 * Search array and get first matched.
 */
function uc_array_search( $search, $array ){
    foreach( $array as $item ){
		foreach( $item as $key => $val ) {
			if ( is_array( $val ) ) {
				foreach( $val as $metakey => $metavalue ) {
					if ( $metavalue == $search ) {
						return $item;
					}
				}
			}
		}
    }
	return false;
}