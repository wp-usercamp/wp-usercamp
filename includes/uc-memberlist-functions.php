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
			$the_memberlist = new UC_Memberlist();
			$the_memberlist->set( 'post_title', isset( $data['title'] ) ? uc_clean( $data['title'] ) : '' );
			$the_memberlist->set( 'post_name', uc_clean( wp_unslash( $key ) ) );
			$the_memberlist->set( 'meta_input', array(
					'per_page'			=> 15,
					'per_row'			=> 3,
					'use_ajax'			=> 'yes',
					'roles'				=> array( '_all' ),
			) );
			$the_memberlist->insert();
			$the_memberlist->save( $the_memberlist->meta_input );

			// Do this once only.
			if ( ! get_option( 'usercamp_default_memberlist' ) ) {
				update_option( 'usercamp_default_memberlist', $the_memberlist->id );
			}
		}
	}

}