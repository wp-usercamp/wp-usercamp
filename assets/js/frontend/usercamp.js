jQuery( function( $ ) {

	// Tooltips
	$( document.body ).on( 'uc-init-tooltips', function() {
		$( '.usercamp-help-tip, .tips' ).tipTip( {
			'attribute': 'data-tip',
			'fadeIn': 150,
			'fadeOut': 150,
			'delay': 300
		} );
	}).trigger( 'uc-init-tooltips' );

	// Body events
	$( document.body )

		// No href
		.on( 'click', '.usercamp a[href=#]', function(e) {
			e.preventDefault();
		} )

		.on( 'click', '.uc-pw-visible', function(e) {
			c = $( this );
			if ( c.hasClass( 'is-hidden' ) ) {
				c.addClass( 'is-visible' ).removeClass( 'is-hidden' ).attr( 'data-tip', c.attr( 'data-hide') ).html( '<i data-feather=eye></i>' ).parents( '.usercamp-field' ).find( 'input[type=password]' ).attr( 'type', 'text' ).focus();
			} else {
				c.addClass( 'is-hidden' ).removeClass( 'is-visible' ).attr( 'data-tip', c.attr( 'data-show') ).html( '<i data-feather=eye-off></i>' ).parents( '.usercamp-field' ).find( 'input[type=text]' ).attr( 'type', 'password' ).focus();
			}
			$( document.body ).trigger( 'uc-init-tooltips' );
			feather.replace();
		} )

		// Send form
		.on( 'click', '.usercamp-button.main:not(.disabled)', function(e) {
			var f = $( this );
			f.addClass( 'disabled' );
			f.parents( 'form' ).submit();
		} )

		// Submit form
		.on( 'submit', '.usercamp form', function(e) {
			var t = $( this );

			if ( t.attr( 'data-ajax' ) != 'yes' ) {
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

// This runs after everyhing is loaded.
jQuery( window ).load( function() {

	feather.replace();

} );