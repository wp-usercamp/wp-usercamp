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
		active_field:	null,

		// Get form data
		getdata: function() {

			// Data objects
			var data = [];

			// Fields
			data['fields'] = [];
			$( '.uc-bld-col .uc-bld-elem:visible' ).each( function() {
				var thisdata = {};
				var that = $( this );
				$.each( usercamp_admin.metakeys, function( index, key ) {
					var attribute = that.attr( 'data-' + key );
					if ( attribute ) {
						thisdata[ key ] = attribute;
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

			var placeholder = $( '.uc-bld-elem.hidden:first' );
			var $e = placeholder.clone().appendTo( this.active );
			var icon = el.attr( 'data-icon' ) ? el.attr( 'data-icon' ) : el.attr( 'data-noicon' );

			$e.html( $e.html().replace( /{label}/i, el.html() ) );
			$e.html( $e.html().replace( /{key}/i, el.attr( 'data-key' ) ) );
			$e.find( '.uc-bld-icon svg use' ).attr( 'xlink:href', usercamp_admin.svg + icon );

			$.each( el.data(), function ( name, value ) {
				$e.attr( 'data-' + name, value );
			} );

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
				el.find( '.uc-toggle-row svg use' ).attr( 'xlink:href', usercamp_admin.svg + 'chevron-up' );
			} else {
				el.attr( 'data-toggled', 1 ).find( '.uc-bld-col, .uc-bld-add, .uc-bld-bar' ).hide();
				el.find( '.uc-bld-cols' ).height( 20 );
				el.find( '.uc-toggle-row svg use' ).attr( 'xlink:href', usercamp_admin.svg + 'chevron-down' );
			}
		},

		// Ready to save
		ready_save: function() {
			$.modal.close();
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
		},

		// Clears modal settings.
		clear_modal_settings: function( el ) {
			var m = $( '#uc-add-field' );

			// Change heading.
			m.find( 'h3' ).html( usercamp_admin.modal.creating );

			// Unlock key.
			m.find( '#key' ).removeAttr( 'disabled' );

			// Change buttons.
			m.find( '.button-primary' ).addClass( 'add_field' ).removeClass( 'save_field' ).html( usercamp_admin.modal.create_button );
			m.find( '.button-secondary' ).attr( 'rel', 'modal:open' );

			// Clear fields as much as we can.
			m.find( 'input[type=text], textarea' ).val( '' );
			m.find( 'select' ).each( function() {
				$( this )[0].selectize.setValue( '', true );
			} );

			// Set the field type.
			m.find( '#type' )[0].selectize.setValue( el.attr( 'data-type' ), true );	
		},

		// Edit field.
		edit_field: function( el ) {
			this.active_field = el;
			this.add_modal_settings( el );
		},

		// Add settings to field modal.
		add_modal_settings: function( el ) {
			var m = $( '#uc-add-field' );

			// Change heading.
			m.find( 'h3' ).html( usercamp_admin.modal.editing.replace( '{field}', el.attr( 'data-label' ) ) );

			// Lock key.
			m.find( '#key' ).attr( 'disabled', 'disabled' );

			// Change buttons.
			m.find( '.button-primary' ).removeClass( 'add_field' ).addClass( 'save_field' ).html( usercamp_admin.modal.save_button );
			m.find( '.button-secondary' ).attr( 'rel', 'modal:close' );

			// loop through each data attribute and set the option in field settings modal.
			$.each( usercamp_admin.metakeys, function( index, key ) {
				var input = m.find( '#' + key );
				var value = el.attr( 'data-' + key );
				if ( input.length ) {
					var type = input.parents( 'fieldset' ).attr( 'data-type' );
					if ( type == 'select' ) {
						if ( input.prop('multiple') ) {
							if ( value ) {
								var array = value.split(',');
								input[0].selectize.setValue( array, true );
							}
						} else {
							input[0].selectize.setValue( value, true );
						}
					} else if ( type == 'switch' ) {
						if ( value == 1 ) {
							input.parents( 'fieldset' ).find( '.uc-toggle' ).toggles( true );	
						}
					} else {
						input.val( value );
					}
				}
			} );
		},

		// Save field.
		save_field: function( el ) {
			this.save_modal_settings( el );
			this.sortables();
			this.ready_save();
		},

		// Save settings from field modal.
		save_modal_settings: function( el ) {
			var m = $( '#uc-add-field' );
			$.each( usercamp_admin.metakeys, function( index, key ) {
				var input = m.find( '#' + key );
				var value = '';
				if ( input.length ) {
					var type = input.parents( 'fieldset' ).attr( 'data-type' );
					if ( type == 'switch' ) {
						value = ( input.parents( 'fieldset' ).find( ':checkbox' ).is( ':checked' ) ) ? 1 : '';
					} else {
						value = input.val();
					}
					if ( key == 'label' ) {
						el.find( '.uc-bld-label' ).html( value );
					}
					if ( key == 'icon' ) {
						if ( value ) {
							el.find( '.uc-bld-icon svg use' ).attr( 'xlink:href', usercamp_admin.svg + value );
						} else {
							el.find( '.uc-bld-icon svg use' ).attr( 'xlink:href', usercamp_admin.svg + el.attr( 'data-noicon' ) );
						}
					}
					el.attr( 'data-' + key, value );
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
	} ).trigger( 'uc-init-tabbed-panels' );

	// Conditional fields
	$( document.body ).on( 'uc-init-fields', function() {
		$( '.usercamp_options_panel:visible fieldset[class*=show_if_]' ).conditional();
	} ).trigger( 'uc-init-fields' );

	// Toggles
	$( document.body ).on( 'uc-init-toggles', function() {
		$( '.uc-toggle' ).each( function() {
			var cb = $( this ).parents( 'fieldset' ).find( 'input[type=checkbox]' );
			$( this ).toggles( {
				'width': 60,
				'height': 20,
				'text': {
					on: usercamp_admin.yes,
					off: usercamp_admin.no
				},
				'checkbox': cb
			} ).on( 'toggle', function(e, active) {
				$( document.body ).trigger( 'uc-init-fields' );
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

	// Selectize
	$( document.body ).on( 'uc-init-selects', function() {
		$( '.uc-select' ).selectize();
		$( '.uc-select-multi' ).selectize( {
			plugins: [ 'remove_button', 'drag_drop' ],
			delimiter: ',',
			persist: false,
			create: function( input ) {
				return {
					value: input,
					text: input
				}
			}
		} );
	} ).trigger( 'uc-init-selects' );

	// Show additional title action if required
	$( document.body ).on( 'uc-init-action-button', function() {
		var el = $( '.uc-page-title-action' );
		$( '.page-title-action' ).after( el );
		el.show();
	} ).trigger( 'uc-init-action-button' );

	// Init builder
	$( document.body ).on( 'uc-init-builder', function() {
		uc_builder.freshstart();
		uc_builder.sortables();
	} ).trigger( 'uc-init-builder' );

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

		// Ajax save field.
		.on( 'click', '.add_field:not(.disabled)', function() {
			uc_builder.add_field( $( this ) );
		} )

		// New field.
		.on( 'click', '.new_field', function() {
			uc_builder.clear_modal_settings( $( this ) );
		} )

		// Edit field.
		.on( 'click', '.uc-edit-field', function() {
			uc_builder.edit_field( $( this ).parents( '.uc-bld-elem' ) );
		} )

		// Save field.
		.on( 'click', '.save_field', function() {
			uc_builder.save_field( uc_builder.active_field );
		} )

		// Before field modal is open.
		.on( $.modal.BEFORE_OPEN, '#uc-add-field', function(e, modal) {
			$( '.modal span.error' ).remove();
			$( '.modal :input' ).removeClass( 'error' );
			$( '.modal div.panel-wrap' ).each( function() {
				$( this ).find( 'ul.uc-tabs li' ).eq( 0 ).find( 'a' ).click();
			});
		} )

		// After field modal is open.
		.on( $.modal.OPEN, '#uc-add-field', function(e, modal) {
			$( document.body ).trigger( 'uc-init-fields' );
			modal.options.clickClose = false;
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
			if ( $( this ).is( ':checked' ) ) {
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
				location.reload();
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
		.on( 'click', '.uc-rule .button', function( e ) {
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
				txt.val( txt.val().replace( pattern, '' ) );
				item.remove();
			}
		} )

		// Add rule.
		.on( 'click', '.rule_actions a.add', function( e ) {
			var el = $( this ).parents( '.uc-rule' ),
				key = el.find( '#rule_key' ).val(),
				exp = el.find( '#rule_exp' ).val(),
				val = el.find( '#rule_val' ).val(),
				txt = el.find( 'textarea' ),
				pattern = '{' + encodeURIComponent(key) + '|' + encodeURIComponent(exp) + '|' + encodeURIComponent(val) + '}';

			if ( ! val ) {
				$( '#rule_val' ).focus().change();
				return false;
			}

			txt.val( txt.val() + pattern );

			el.find( '#rule_val' ).val( '' );
			el.find( '.uc-rule-new' ).css( { 'padding-top' : 0, 'opacity' : 0, 'display' : 'none' } );

			var $newitem = $( '.uc-rule-item:hidden' ).clone().appendTo( '.uc-rule-list' ).fadeIn( 'fast' ).removeClass( 'hidden' );
			$newitem.html( $newitem.html().replace( '{key}', $( '#rule_key option:selected' ).text() ) );
			$newitem.html( $newitem.html().replace( '{operator}',  $( '#rule_exp option:selected' ).text() ) );
			$newitem.html( $newitem.html().replace( '{value}', val ) );
			$newitem.attr( 'data-pattern', pattern );
		} );

} );