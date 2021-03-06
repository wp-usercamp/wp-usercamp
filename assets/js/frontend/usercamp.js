jQuery( function( $ ) {

	// Global function.
	var usercamp = {

		// Toggle password visibility.
		togglepw: function( el ) {
			if ( el.hasClass( 'is-hidden' ) ) {
				el.find( 'svg use' ).attr( 'xlink:href', usercamp_params.svg + 'eye' );
				el.addClass( 'is-visible' ).removeClass( 'is-hidden' ).attr( 'data-tip', el.attr( 'data-hide') ).parents( '.usercamp-field' ).find( 'input[type=password]' ).attr( 'type', 'text' ).focus();
			} else {
				el.find( 'svg use' ).attr( 'xlink:href', usercamp_params.svg + 'eye-off' );
				el.addClass( 'is-hidden' ).removeClass( 'is-visible' ).attr( 'data-tip', el.attr( 'data-show') ).parents( '.usercamp-field' ).find( 'input[type=text]' ).attr( 'type', 'password' ).focus();
			}
			$( document.body ).trigger( 'uc-init-tooltips' );
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
			$.post( usercamp_params.ajaxurl, el.serialize() + '&action=usercamp_send_form', function(response) {
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
						if ( response.cleardata ) {
							el.find( ':input:not([type=hidden])' ).val( '' );
						}
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
				'width': 60,
				'height': 20,
				'text': {
					on: usercamp_params.yes,
					off: usercamp_params.no
				},
				'checkbox' : $( '#' + cb )
			} );
		} );
	} ).trigger( 'uc-init-toggles' );

	// Go to focus on first input.
	$( document.body ).on( 'uc-init-focus', function() {
		$( '.usercamp-edit-password' ).find( ':input[value=""]:enabled:visible:first' ).focus();
	} ).trigger( 'uc-init-focus' );

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

		// When helper is used as label.
		.on( 'click', '.usercamp-helper-label', function() {
			var cb = $( this ).parents( 'fieldset' ).find( 'input[type=checkbox]' );
			if ( cb.is( ':checked' ) ) {
				cb.prop( 'checked', false ).change();
			} else {
				cb.prop( 'checked', true ).change();
			}
		} )

		// Listen to checkbox change.
		.on( 'change', '.usercamp-field input[type=checkbox]', function() {
			if ( $(this).is( ':checked' ) ) {
				$( this ).parents( 'fieldset' ).find( '.uc-toggle' ).toggles( true );
			} else {
				$( this ).parents( 'fieldset' ).find( '.uc-toggle' ).toggles( false );
			}
		} )

		// Autotyping for username.
		.on( 'keyup', '.user_login_field input[type=text]', function() {
			var field = $( this ).parents( 'fieldset' );
			if ( field.find( '.uc-ajax' ).length ) {
				field.find( '.uc-ajax' ).html( $( this ).val() );
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