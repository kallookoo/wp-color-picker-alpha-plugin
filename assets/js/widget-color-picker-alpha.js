/**
 * Color picker widget.
 */

( function( $ ) {
	function colorPickerSetup( widget ) {
		// Note: '.form' appears in the customizer, whereas 'form' on the widgets admin screen, '> form' in accessibility mode.
		var form = widget.find( '> .widget-inside > .form, > .widget-inside > form, > form' );

		// Note: '> .id_base' appears in the customizer and admin screen, '> .widget-control-action > .id_base' in accessibility mode.
		if ( -1 !== form.find( '> .id_base, > .widget-control-actions > .id_base' ).val().indexOf( 'color-picker-alpha' ) ) {
			// Use :not(.wp-color-picker) to prevent multiple callbacks.
			widget.find( '.color-picker-widget:not(.wp-color-picker)' ).wpColorPicker( {
				change: function( event, ui ) {
					var color = $( this ).val().replace( /\s+/g, '' ),
						type  = $( this ).wpColorPicker( 'option', 'type' ),
						// Check the type and define the correct value.
						current = ( 'full' === type ? $( this ).iris( 'color' ) : ui.color.h() );

					// It is only updated if they are different values.
					if ( color !== current.replace( /\s+/g, '' ) ) {
						$( this ).val( current ).change();
					}
				}
			} );
		}
	}

	$( function() {
		// Accessibility mode.
		if ( $( 'body' ).hasClass( 'widgets_access' ) ) {
			colorPickerSetup( $( '.editwidget' ) );
		// Normal mode.
		} else {
			// Setup on added or updated.
			$( document ).on( 'widget-added widget-updated', function( event, widget ) {
				colorPickerSetup( widget );
			} );

			// Setup on pre-existing widget
			$( '.widgets-holder-wrap:not(#available-widgets)' ).on( 'click', '.widget:not(.open)', function( event, el ) {
				colorPickerSetup( $( this ) );
			} );
		}
	} );
} )( jQuery );