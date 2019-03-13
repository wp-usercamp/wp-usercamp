jQuery( function ( $ ) {

	// Global function.
	var uc_builder = {

		// Defaults
		default_state: 	$( '.save_form' ).find( 'span' ).html(),
		save_btn: 		$( '.save_form' ).find( 'span' ),
		btn:			$( '.save_form' ),
		state:			$( '.uc-bld-topbar span.description' ),
		hidden_row:		$( '.uc-bld-row.hidden' ),
		new_row:		$( '.uc-bld-new' ),
		active:			null,
		autosave:		1,
		field_type:		null,

		// Get form data
		getdata: function() {

			// Data objects
			var data = [];

			// Fields
			data['fields'] = [];
			$( '.uc-bld-col .uc-bld-elem:visible' ).each( function() {
				var thisdata = {};
				$.each( $( this ).data(), function( k, v ) {
					if ( typeof v === 'string' ) {
						thisdata[ k ] = v;
					}
				} );
				data.fields.push( {
					'data'	 : thisdata,
					'row' 	 : $( this ).parents( '.uc-bld-row' ).index( '.uc-bld-row' ),
					'col'	 : $( this ).parents( '.uc-bld-col' ).index() + 1
				} );
			} );

			// Columns
			data['cols'] = [];
			data.cols.push( { count: 0, layout: 0 } );
			$( '.uc-bld-row:not(.hidden)' ).each( function() {
				var size = [];
				$( this ).find( '.uc-bld-col' ).each( function() {
					size.push( $( this ).getsize() );
				} );
				data.cols.push( {
					'count'  : $( this ).find( '.uc-bld-col' ).length,
					'layout' : size
				} );
			} );

			// Global
			data = $.extend( {
				id: 		this.btn.attr( 'data-id' ),
				action: 	'usercamp_save_form',
				security: 	usercamp_admin.nonces.save_form,
				row_count:	$( '.uc-bld-row:not(.hidden)' ).length
			}, data );

			return data;
		},

		// Init
		freshstart: function() {
			if ( $( '.uc-bld-row' ).length == 0 ) {
				return true;
			}

			if ( this.autosave ) {
				this.btn.hide();
			}

			if ( $( '.uc-bld-row:not(.hidden)' ).length == 0 ) {
				var $new_row = this.hidden_row.clone().insertBefore( this.new_row ).show().removeClass( 'hidden' ).append( '<div class="uc-clear"></div>' );
				$new_row.find( '.uc-bld-elems' ).empty();
				$new_row.find( 'a.grid:first' ).addClass( 'active' );
			}

			// loop through each row
			$( '.uc-bld-row:not(.hidden)' ).each( function() {
				var grid 	= 0,
					p		= 0,
					cols 	= $( this ).find( '.uc-bld-col' ).length;

				// calculate grid
				$( this ).find( '.uc-bld-col' ).each( function() {
					grid = p += $( this ).getsize();
				} );

				// loop through columns
				i = 0;
				$( this ).find( '.uc-clear' ).remove();
				$( this ).find( '.uc-bld-col' ).each( function() {
					i++;
					var gut 	= ( 100 - grid ) / cols,
						size 	= $( this ).getsize() + gut;

					if ( size == 33.5 ) { size = 33.33; } else if ( size == 66.5 ) { size = 66.66; }

					if ( i == cols ) {
						$( this ).css( {
							'width'			: parseFloat( size ).toFixed( 2 ) + '%',
							'border-right'	: '0'
						} )
						.after( '<div class="uc-clear"></div>' );
					} else {
						$( this ).css( {
							'width'			: parseFloat( size ).toFixed( 2 ) + '%',
							'border-right'	: '5px solid #fff'
						} );
					}
				} );

			} );
		},

		// Add row
		add_row: function() {
			var $new_row = this.hidden_row.clone().insertBefore( this.new_row ).show().removeClass( 'hidden' ).append( '<div class="uc-clear"></div>' );
			$new_row.find( '.uc-bld-elems' ).empty();
			$new_row.find( 'a.grid:first' ).addClass( 'active' );
			this.sortables();
			this.ready_save();
		},

		// Duplicate row
		duplicate_row: function( el ) {
			var $dup_row = el.clone().insertAfter( el );
			this.sortables();
			this.ready_save();
		},

		// Delete row
		delete_row: function( el ) {
			el.remove();
			this.ready_save();
		},

		// Duplicate field
		duplicate_field: function( el ) {
			var $dup_field = el.clone().insertAfter( el );
			this.sortables();
			this.ready_save();
		},

		// Delete element
		delete_field: function( el ) {
			el.remove();
			this.ready_save();
		},

		// Insert element
		insert: function( el ) {
			var $e = $( '.uc-bld-elem.hidden:first' ).clone().appendTo( this.active );
			$e.html( $e.html().replace( /{label}/i, el.html() ) );
			$e.html( $e.html().replace( /{key}/i, el.attr( 'data-key' ) ) );
			$e.html( $e.html().replace( /{icon}/i, el.attr( 'data-icon' ) ? el.attr( 'data-icon' ) : '<i data-feather="'+ el.attr( 'data-noicon' ) +'"></i>' ) );
			$.each( el.data(), function (name, value) { $e.attr( 'data-'+name, value ); } );
			$e.show().removeClass( 'hidden' );
			this.sortables();
			this.ready_save();
		},

		// Layout changes
		layout: function( r, g ) {
			var s = g.split( ':' ),
				c = r.find( '.uc-bld-col' ),
				e = $( '.uc-bld-row.hidden' ).find( '.uc-bld-col' );

			if ( s[0] == '100' ) {
				s = [100];
			}

			// We need to add more cols.
			if ( s.length > c.length ) {
				$missing_cols = s.length - c.length;
				for ( var i = 0; i < $missing_cols; i++ ) {
					var $n = e.clone().appendTo( r );
				}
			}

			// We need to remove extra cols.
			if ( s.length < c.length ) {
				$overhead_cols = c.length - s.length;
				for ( var i = 0; i < $overhead_cols; i++ ) {
					r.find( '.uc-bld-col:last' ).remove();
				}
			}

			i = 0;
			r.find( '.uc-bld-col' ).each( function() {
				$( this ).attr( 'class', 'uc-bld-col grid-' + s[i] );
				i++;
			} );

			this.freshstart();
			this.sortables();
			this.ready_save();
		},

		// Sortables
		sortables: function() {
			// Reorder elements.
			$( '.uc-bld-elems' ).sortable({
				connectWith: '.uc-bld-elems',
				handle:	'.uc-move-field',
				placeholder: 'uc-placeholder',
				tolerance: 'pointer',
				appendTo: $( 'body' ),
				start: function( event, ui ) {
					ui.placeholder.height( ( ui.item.height() - 2 ) );
				},
				update: function( event, ui ) {
					uc_builder.ready_save();
				}
			});

			// Reorder rows.
			$( '#usercamp-form-builder .inside' ).sortable({
				items: '> .uc-bld-row',
				axis: 'y',
				handle:	'.uc-move-row',
				placeholder: 'uc-placeholder-row',
				tolerance: 'pointer',
				appendTo: $( 'body' ),
				start: function( event, ui ) {
					ui.placeholder.height( ( ui.item.height() - 2 ) );
				},
				update: function( event, ui ) {
					uc_builder.ready_save();
				}
			});
		},

		// Toggle
		toggle: function( el ) {
			if ( el.attr( 'data-toggled' ) == 1 ) {
				el.attr( 'data-toggled', 0 ).find( '.uc-bld-col, .uc-bld-add, .uc-bld-bar' ).show();
				el.find( '.uc-bld-cols' ).height( 'auto' );
				el.find( '.uc-toggle-row' ).html( '<i data-feather="chevron-up"></i>' );
				feather.replace();
			} else {
				el.attr( 'data-toggled', 1 ).find( '.uc-bld-col, .uc-bld-add, .uc-bld-bar' ).hide();
				el.find( '.uc-bld-cols' ).height( 20 );
				el.find( '.uc-toggle-row' ).html( '<i data-feather="chevron-down"></i>' );
				feather.replace();
			}
		},

		// Ready to save
		ready_save: function() {
			$.modal.close();
			feather.replace();
			if ( this.autosave ) {
				this.save();
			} else {
				this.btn.removeClass( 'disabled' );
			}
		},

		// Saving in progress
		saving: function() {
			this.save_btn.html( usercamp_admin.states.saving_changes ).parent().addClass( 'disabled' );
			this.state.html( '<span class="spinner"></span>' + usercamp_admin.states.saving_changes );
			$( '#publish' ).attr( 'disabled', 'disabled' );
		},

		// Saved
		saved: function() {
			this.state.empty();
			this.save_btn.html( this.default_state );
			$( '#publish' ).removeAttr( 'disabled' );
		},

		// Save form
		save: function() {
			this.saving();
			$.ajax( {
				url: ajaxurl,
				data: this.getdata(),
				dataType: 'json',
				type: 'post',
				context: this,
				success: function() {
					setTimeout( () => { this.saved(); }, 1000 );
				}
			} );
		},

		// Add field
		add_field: function( el ) {
			var html = el.html(),
				data = $( '#uc-add-field :input' ).serialize();

			el.addClass( 'disabled' ).html( usercamp_admin.states.saving_changes );

			$.ajax( {
				url: ajaxurl,
				data: data + '&action=usercamp_add_field&security=' + usercamp_admin.nonces.add_field,
				dataType: 'json',
				type: 'post',
				context: this,
				success: function( res ) {
					el.removeClass( 'disabled' ).html( html );
					if ( res.errors ) {
						$( 'span.error' ).remove();
						$( ':input' ).removeClass( 'error' );
						$.each( res.errors, function(key, error) {
							$( '#' + key ).addClass( 'error' ).parents( 'fieldset' ).append( '<span class="error">' + error + '</span>' );
							$( 'span.error' ).animate( { 'opacity' : 1, 'top' : '0' }, 250 );
						} );
					} else {
						this.insert( $( res.data ) );
					}
				}
			} );
		}

	}

	// Tabbed Panels
	$( document.body ).on( 'uc-init-tabbed-panels', function() {
		$( 'ul.uc-tabs' ).show();
		$( 'ul.uc-tabs a' ).click( function( e ) {
			e.preventDefault();
			var panel_wrap = $( this ).closest( 'div.panel-wrap' );
			$( 'ul.uc-tabs li', panel_wrap ).removeClass( 'active' );
			$( this ).parent().addClass( 'active' );
			$( 'div.panel', panel_wrap ).hide();
			$( $( this ).attr( 'href' ) ).show();
		});
		$( 'div.panel-wrap' ).each( function() {
			$( this ).find( 'ul.uc-tabs li' ).eq( 0 ).find( 'a' ).click();
		});
	}).trigger( 'uc-init-tabbed-panels' );

	// Conditional fields
	$( document.body ).on( 'uc-init-fields', function() {
		$( '.usercamp_options_panel:visible fieldset[class*=show_if_]' ).conditional();
	}).trigger( 'uc-init-fields' );

	// Toggles
	$( document.body ).on( 'uc-init-toggles', function() {
		$( '.uc-toggle' ).toggles( {
			width: 50,
			height: 20,
			text: {
				on: '',
				off: ''
			}
		} ).on( 'toggle', function( e, active ) {
			if ( active ) {
				$( this ).parents( '.form-field' ).find( ':checkbox' ).prop( 'checked', true );
			} else {
				$( this ).parents( '.form-field' ).find( ':checkbox' ).prop( 'checked', false );
			}
			$( document.body ).trigger( 'uc-init-fields' );
		});
	}).trigger( 'uc-init-toggles' );

	// Tooltips
	$( document.body ).on( 'uc-init-tooltips', function() {
		$( '.usercamp-help-tip, .tips' ).tipTip( {
			'attribute': 'data-tip',
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200
		} );
	}).trigger( 'uc-init-tooltips' );

	// Show additional title action if required
	$( document.body ).on( 'uc-init-action-button', function() {
		var el = $( '.uc-page-title-action' );
		$( '.page-title-action' ).after( el );
		el.show();
	}).trigger( 'uc-init-action-button' );

	// Init builder
	$( document.body ).on( 'uc-init-builder', function() {
		uc_builder.freshstart();
		uc_builder.sortables();
	}).trigger( 'uc-init-builder' );

	// Init selectize
	$( document.body ).on( 'uc-init-selects', function() {
		$( '.uc-select' ).selectize();
		$( '.uc-select-multi' ).selectize({
			plugins: ['remove_button', 'drag_drop'],
			delimiter: ',',
			persist: false,
			create: function(input) {
				return {
					value: input,
					text: input
				}
			}
		});
	} ).trigger( 'uc-init-selects' );

	// Document triggers.
	$( document.body )

		// Delete field.
		.on( 'click', '.uc-delete-field', function() {
			uc_builder.delete_field( $( this ).parents( '.uc-bld-elem' ) );
		} )

		// Delete row.
		.on( 'click', '.uc-delete-row', function() {
			uc_builder.delete_row( $( this ).parents( '.uc-bld-row' ) );
		} )

		// Add row.
		.on( 'click', '.uc-add-row', function() {
			uc_builder.add_row();
		} )

		// Insert field.
		.on( 'click', '.insert_field', function() {
			uc_builder.insert( $( this ) );
		} )

		// Add element.
		.on( 'click', '.uc-add-element', function() {
			uc_builder.active = $( this ).parents( '.uc-bld-col' ).find( '.uc-bld-elems' );
		} )

		// Toggle row.
		.on( 'click', '.uc-toggle-row', function() {
			uc_builder.toggle( $( this ).parents( '.uc-bld-row' ) );
		} )

		// Duplicate row.
		.on( 'click', '.uc-duplicate-row', function() {
			uc_builder.duplicate_row( $( this ).parents( '.uc-bld-row' ) );
		} )

		// Duplicate field.
		.on( 'click', '.uc-duplicate-field', function() {
			uc_builder.duplicate_field( $( this ).parents( '.uc-bld-elem' ) );
		} )

		// Adjust row grid.
		.on( 'click', '.uc-bld-row a.grid', function() {
			$( this ).parents( '.uc-bld-row' ).find( 'a.grid' ).removeClass( 'active' );
			$( this ).addClass( 'active' );
			uc_builder.layout( $( this ).parents( '.uc-bld-row' ).find( '.uc-bld-cols' ), $( this ).attr( 'data-layout' ) );
		} )

		// Save form.
		.on( 'click', '.save_form:not(.disabled)', function() {
			uc_builder.save();
		} )

		// Save field.
		.on( 'click', '.add_field:not(.disabled)', function() {
			uc_builder.add_field( $( this ) );
		} )

		// New field.
		.on( 'click', '.new_field', function() {
			uc_builder.field_type = $( this ).attr( 'data-type' );
			$( '#type' )[0].selectize.setValue( uc_builder.field_type, true );
		} )

		// After field modal is open.
		.on( $.modal.OPEN, '#uc-add-field', function(e, modal) {
			$( document.body ).trigger( 'uc-init-fields' );
			modal.options.clickClose = false;
		} )

		// Before field modal is open.
		.on( $.modal.BEFORE_OPEN, '#uc-add-field', function(e, modal) {
			$( 'span.error' ).remove();
			$( ':input' ).removeClass( 'error' );
		} )

		// See more or less.
		.on( 'click', '.uc-togglemore', function( e ) {
			e.preventDefault();
			var el = $( this ),
				contain = el.parents( 'td' );
			if ( contain.find( 'span.hidden' ).length ) {
				el.appendTo( contain ).html( usercamp_admin.states.show_less );
				contain.find( 'span.hidden' ).removeClass( 'hidden' ).addClass( 'js-temp' );
			} else {
				el.html( usercamp_admin.states.show_more );
				contain.find( 'span.js-temp' ).addClass( 'hidden' ).removeClass( 'js-temp' );
			}
		} )

		// No links here.
		.on( 'click', ".usercamp_options_panel a[href=#], .uc-bld-topbar a[href=#], .uc-bld-row a[href=#], .uc-bld-new a[href=#], .modal a[href=#], a[class^='duplicate_'].disabled", function(e) {
			e.preventDefault();
		} )

		// Listen to checkbox change.
		.on( 'change', '.options_group input[type=checkbox]', function() {
			if ( $(this).is( ':checked' ) ) {
				$( this ).parents( '.form-field' ).find( '.uc-toggle' ).toggles( true );
			} else {
				$( this ).parents( '.form-field' ).find( '.uc-toggle' ).toggles( false );
			}
		} )

		// Listen to select change.
		.on( 'change', '.options_group select', function() {
			$( document.body ).trigger( 'uc-init-fields' );
		} )

		// Create items.
		.on( 'click', "a[href^='#uc-create-']", function( e ) {
			e.preventDefault();

			var el = $( this ),
				type = el.attr( 'href' ).split( '#uc-create-' )[1];

			if ( type == 'forms' ) {
				label = usercamp_admin.states.create_forms;
				nonce = usercamp_admin.nonces.create_forms;
			} else if ( type == 'fields' ) {
				label = usercamp_admin.states.create_fields;
				nonce = usercamp_admin.nonces.create_fields;
			} else if ( type == 'roles' ) {
				label = usercamp_admin.states.create_roles;
				nonce = usercamp_admin.nonces.create_roles;
			} else if ( type == 'memberlists' ) {
				label = usercamp_admin.states.create_memberlists;
				nonce = usercamp_admin.nonces.create_memberlists;
			}

			el.addClass( 'disabled' ).html( label );

			$.post( ajaxurl, { action: 'usercamp_create_' + type, security: nonce }, function(response) {
				el.html( usercamp_admin.states.done_redirect );
				setTimeout( function() { location.reload(); }, 3000 );
			} );

		} )

		// Duplicate item.
		.on( 'click', "a[class^='duplicate_']:not(.disabled)", function( e ) {
			e.preventDefault();

			var el 		= $( this ),
				data 	= { action: 'usercamp_' + el.attr( 'class' ), security: el.getparam( '_wpnonce' ), id: el.getparam( 'post' ) };

			el.addClass( 'disabled' ).html( usercamp_admin.states.duplicating );

			$.post( ajaxurl, data, function(response) { location.reload(); } );

		} )

		// New rule.
		.on( 'click', '.uc-rule .uc-tag-icon', function( e ) {
			var el = $( this ).parents( '.uc-rule' );
			el.find( '.uc-rule-new' ).show().animate( { 'padding-top' : '15px', 'opacity' : 1 }, 250 );
		} )

		// Cancel rule.
		.on( 'click', '.rule_actions a.remove', function( e ) {
			var el 		= $( this ).parents( '.uc-rule' ),
				item 	= $( this ).parents( '.uc-rule-item' ),
				txt		= el.find( 'textarea' );

			el.find( '.uc-rule-new' ).css( { 'padding-top' : 0, 'opacity' : 0, 'display' : 'none' } );

			if ( item.length ) {
				var pattern = item.attr( 'data-pattern' );
				txt.val( txt.val().replace( '{' + pattern + '}', '' ) );
				item.remove();
			}
		} )

		// Add rule.
		.on( 'click', '.rule_actions a.add', function( e ) {
			var el = $( this ).parents( '.uc-rule' ),
				key = el.find( '#rule_key' ).val(),
				exp = el.find( '#rule_exp' ).val(),
				val = el.find( '#rule_val' ).val();

			if ( ! val ) {
				$( '#rule_val' ).focus().change();
				return false;
			}

			var rule = '{' + encodeURIComponent(key) + '|' + encodeURIComponent(exp) + '|' + encodeURIComponent(val) + '}';
			var txt = el.find( 'textarea' );
			txt.val( txt.val() + rule );

			el.find( '#rule_val' ).val( '' );
			el.find( '.uc-rule-new' ).css( { 'padding-top' : 0, 'opacity' : 0, 'display' : 'none' } );
		} );

} );

// This runs after everyhing is loaded.
jQuery( window ).load( function() {

	feather.replace();

} );