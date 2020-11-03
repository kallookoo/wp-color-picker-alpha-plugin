/**
 * Color picker customize control.
 */

( function ( $, api ) {
	/**
	 * A colorpicker control.
	 *
	 * @class    wp.customize.AlphaColorControl
	 * @augments wp.customize.Control
	 */
	api.AlphaColorControl = api.Control.extend(/** @lends wp.customize.AlphaColorControl.prototype */{
		ready: function() {
			var control = this,
				isHueSlider = ( this.params.mode === 'hue' ),
				picker = this.container.find( '.color-picker-customize' ),
				inited = false,
				color = picker.val().replace( /\s+/g, '' );

			picker.wpColorPicker( {
				change: function( event, ui ) {
					var current = ( isHueSlider ? ui.color.h() : picker.wpColorPicker( 'color' ) );
					// It is only updated if they are different values.
					if ( color !== current.replace( /\s+/g, '' ) ) {
						control.setting.set( current );
					}
				},
				clear: function( event ) {
					// Set setting because not change if empty.
					if ( ! control.setting.get() ) {
						control.setting.set( ' ' );
					}
					control.setting.set( '' );
				}
			} );

			if ( picker.hasClass( 'wp-color-picker' ) ) {
				// Collapse color picker when hitting Esc instead of collapsing the current section.
				control.container.on( 'keydown', function( event ) {
					if ( 27 === event.which ) { // Esc.
						var pickerContainer = control.container.find( '.wp-picker-container' );
						if ( pickerContainer.hasClass( 'wp-picker-active' ) ) {
							picker.wpColorPicker( 'close' );
							control.container.find( '.wp-color-result' ).focus();
							event.stopPropagation(); // Prevent section from being collapsed.
						}
					}
				} );
			}
		}
	} );

	// Register the new Control Constructor.
	api.controlConstructor.alpha_color = api.AlphaColorControl;

} )( jQuery, wp.customize );