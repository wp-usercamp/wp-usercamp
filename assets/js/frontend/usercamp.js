jQuery( function( $ ) {

	// Body events
	$( document.body )

		// No href
		.on( 'click', '.usercamp a[href=#]', function(e) {
			e.preventDefault();
		} )

		// Send form
		.on( 'click', '.usercamp-button.main:not(.disabled)', function(e) {
			var f = $( this );
			f.addClass( 'disabled' );
			f.parents( 'form' ).submit();
		} )

		// Send form
		.on( 'submit', '.usercamp form', function(e) {
			var t = $( this );

			if ( t.attr( 'data-ajax' ) != 'true' ) {
				return true;
			}
			e.preventDefault();

			$.post( usercamp_params.ajaxurl, t.serialize() + '&action=usercamp_send_form&id=' + t.attr( 'data-id' ), function(response) {
				t.find( '.usercamp-button.main' ).removeClass( 'disabled' );
				t.find( '.usercamp-error, .usercamp-message, .usercamp-info' ).remove();
				t.find( '.uc-error' ).removeClass( 'uc-error' );
				t.prepend( response.html );
				if ( response.error_fields ) {
					$.each( response.error_fields, function( i, e ) {
						t.find(e).find( 'label, input' ).addClass( 'uc-error' );
					} );
				} else {
					t.find( ':input:not([type=hidden])' ).val( '' );
				}
			} ).fail( function(xhr, status, error) {
				t.find( '.usercamp-button.main' ).removeClass( 'disabled' );
				t.find( '.usercamp-error, .usercamp-message, .usercamp-info' ).remove();
				t.prepend( '<div class="usercamp-info">' + error + '</div>' );
			} );

		} );

});