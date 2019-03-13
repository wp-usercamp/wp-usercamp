<?php
/**
 * Member List Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get default member lists.
 */
function usercamp_get_default_memberlists() {
	return apply_filters( 'usercamp_get_default_memberlists', array(
		'members'		=> array(
			'title'		=> __( 'Members', 'usercamp' ),
		),
	) );
}

/**
 * Create default member lists.
 */
function usercamp_create_default_memberlists() {

	if ( ! empty( $memberlists = usercamp_get_default_memberlists() ) ) {
		foreach( $memberlists as $key => $data ) {
			$memberlist = new UC_Memberlist();
			$memberlist->set( 'post_title', isset( $data['title'] ) ? uc_clean( $data['title'] ) : '' );
			$memberlist->set( 'post_name', uc_clean( wp_unslash( $key ) ) );
			$field->set( 'meta_input', array(
					'per_page'			=> 15,
					'per_row'			=> 3,
					'use_ajax'			=> 'yes',
			) );
			$memberlist->insert();
			$memberlist->save( $memberlist->meta_input );
		}
	}

}