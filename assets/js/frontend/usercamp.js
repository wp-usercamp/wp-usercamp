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
		} );

});