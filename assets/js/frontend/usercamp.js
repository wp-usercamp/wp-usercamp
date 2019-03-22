jQuery( function( $ ) {

	// Global function.
	var usercamp = {

		// Toggle password visibility.
		togglepw: function( el ) {
			if ( el.hasClass( 'is-hidden' ) ) {
				el.addClass( 'is-visible' ).removeClass( 'is-hidden' ).attr( 'data-tip', el.attr( 'data-hide') ).html( '<i data-feather=eye></i>' ).parents( '.usercamp-field' ).find( 'input[type=password]' ).attr( 'type', 'text' ).focus();
			} else {
				el.addClass( 'is-hidden' ).removeClass( 'is-visible' ).attr( 'data-tip', el.attr( 'data-show') ).html( '<i data-feather=eye-off></i>' ).parents( '.usercamp-field' ).find( 'input[type=text]' ).attr( 'type', 'password' ).focus();
			}
			$( document.body ).trigger( 'uc-init-tooltips' );
			feather.replace();
		},

		// Trigger form.
		triggerform: function( el ) {
			el.addClass( 'disabled' ).parents( 'form' ).submit();
		},

		// Submit form.
		sendform: function( el, ev ) {
			if ( el.attr( 'data-ajax' ) != 'yes' ) {
				return true;
			}
			ev.preventDefault();
			el.find( '.usercamp-error, .usercamp-message, .usercamp-info' ).remove();
			$.post( usercamp_params.ajaxurl, el.serialize() + '&action=usercamp_send_form&id=' + el.attr( 'data-id' ), function(response) {
				el.find( '.uc-error' ).removeClass( 'uc-error' );
				el.prepend( response.html );
				if ( response.error_fields ) {
					el.find( '.usercamp-button.main' ).removeClass( 'disabled' );
					$.each( response.error_fields, function( i, e ) {
						el.find(e).find( 'label, input' ).addClass( 'uc-error' );
					} );
				} else {
					if ( response.js_redirect ) {
						window.location.href = response.js_redirect;
					} else {
						el.find( '.usercamp-button.main' ).removeClass( 'disabled' );
						el.find( ':input:not([type=hidden])' ).val( '' );
					}
				}
			} ).fail( function(xhr, status, error) {
				el.find( '.usercamp-button.main' ).removeClass( 'disabled' );
				el.prepend( '<div class="usercamp-info">' + error + '</div>' );
			} );
		},

	};

	// Tooltips
	$( document.body ).on( 'uc-init-tooltips', function() {
		$( '.usercamp-help-tip, .tips' ).tipTip( {
			'attribute': 'data-tip',
			'fadeIn': 150,
			'fadeOut': 150,
			'delay': 300,
			'defaultPosition': 'top'
		} );
	} ).trigger( 'uc-init-tooltips' );

	// Toggles
	$( document.body ).on( 'uc-init-toggles', function() {
		$( '.uc-toggle' ).each( function() {
			var cb = $( this ).parents( 'fieldset' ).find( 'input:checkbox' ).attr( 'id' );
			$( this ).toggles( {
				'text': {
					on: '',
					off: ''
				},
				'checkbox' : $( '#' + cb )
			} );
		} );
	} ).trigger( 'uc-init-toggles' );

	// Chevrons for list items.
	$( document.body ).on( 'uc-init-chevron', function() {
		$( '.usercamp-nav li a' ).each( function() {
			$( this ).append( '<i data-feather=chevron-right />' );
		} );
	} ).trigger( 'uc-init-chevron' );

	// Body events
	$( document.body )

		// No href
		.on( 'click', '.usercamp a[href=#]', function(e) {
			e.preventDefault();
		} )

		// Focus on field
		.on( 'click', '.uc-icon', function(e) {
			$( this ).parents( 'fieldset' ).find( 'label' ).trigger( 'click' );
		} )

		// Listen to checkbox change.
		.on( 'change', '.usercamp-field input[type=checkbox]', function() {
			if ( $(this).is( ':checked' ) ) {
				$( this ).parents( 'fieldset' ).find( '.uc-toggle' ).toggles( true );
			} else {
				$( this ).parents( 'fieldset' ).find( '.uc-toggle' ).toggles( false );
			}
		} )

		// Toggle password
		.on( 'click', '.uc-pw-visible', function(e) {
			usercamp.togglepw( $( this ) );
		} )

		// Send form
		.on( 'click', '.usercamp-button.main:not(.disabled)', function(e) {
			usercamp.triggerform( $( this ) );
		} )

		// Submit form
		.on( 'submit', '.usercamp form', function(e) {
			usercamp.sendform( $( this ), e );
		} );

});

// This runs after everyhing is loaded.
jQuery( window ).load( function() {

	feather.replace();

} );