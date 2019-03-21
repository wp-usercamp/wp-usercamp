jQuery( function ( $ ) {

	// Toggles
	$( document.body ).on( 'uc-init-toggles', function() {
		$( '.uc-toggle' ).each( function() {
			var cb = $( this ).parents( 'fieldset' ).find( 'input[type=checkbox]' );
			$( this ).toggles( {
				'width': 60,
				'height': 20,
				'text': {
					on: uc_setup_params.yes,
					off: uc_setup_params.no
				},
				'checkbox': cb
			} );
		} )
	} ).trigger( 'uc-init-toggles' );

	// Tooltips
	$( document.body ).on( 'uc-init-tooltips', function() {
		$( '.usercamp-help-tip, .tips' ).tipTip( {
			'attribute': 'data-tip',
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200
		} );
	} ).trigger( 'uc-init-tooltips' );

	// Document triggers.
	$( document.body )

		// Listen to checkbox change.
		.on( 'change', '.uc-setup-content input[type=checkbox]', function() {
			if ( $(this).is( ':checked' ) ) {
				$( this ).parents( 'fieldset' ).find( '.uc-toggle' ).toggles( true );
			} else {
				$( this ).parents( 'fieldset' ).find( '.uc-toggle' ).toggles( false );
			}
		} );

} );

// This runs after everyhing is loaded.
jQuery( window ).load( function() {

	feather.replace();

} );