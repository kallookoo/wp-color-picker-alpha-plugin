<?php
/**
 * Customize Alpha Control.
 *
 * @package WP Color Picker Alpha
 * @subpackage Customize
 */

namespace kallookoo\wpcpa\Customize;

use \WP_Customize_Color_Control;

defined( 'ABSPATH' ) || exit;

/**
 * Class Alpha_Color_Control
 *
 * \WP_Customize_Color_Control is used since it is the same type of control.
 */
class Alpha_Color_Control extends WP_Customize_Color_Control {

	/**
	 * Type.
	 *
	 * @var string
	 */
	public $type = 'alpha_color';

	/**
	 * Enqueue scripts/styles for the color picker.
	 *
	 * @uses WP_Customize_Color_Control::enqueue()
	 */
	public function enqueue() {
		parent::enqueue();

		wp_enqueue_script( WP_COLOR_PICKER_ALPHA_SCRIPT_NAME );

		wp_enqueue_script(
			WP_COLOR_PICKER_ALPHA_SCRIPT_NAME . '-customize-alpha-color-control',
			plugins_url( 'assets/js/customize/alpha-color-control.js', WP_COLOR_PICKER_ALPHA_PLUGIN_FILE ),
			[ 'customize-controls' ],
			time(),
			true
		);
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @uses WP_Customize_Color_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		/** Create the json alpha option using the input attrs. */
		ob_start();
		$this->input_attrs();
		$data = ob_get_clean();

		$this->json['alpha'] = $data;
	}

	/**
	 * Render a JS template for the content of the color picker control.
	 */
	public function content_template() {
		?>
		<# var inputDataAttr = '', isHueSlider = data.mode === 'hue';
		if ( isHueSlider ) {
			inputDataAttr = 'data-type="hue"';
			if ( data.defaultValue && _.isString( data.defaultValue ) ) {
				inputDataAttr += ' data-default-color="' + data.defaultValue + '"';
			}
		} else if ( data.alpha && _.isString( data.alpha ) ) {
			inputDataAttr = data.alpha;
		} #>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<div class="customize-control-content">
			<label><span class="screen-reader-text">{{{ data.label }}}</span>
				<input class="color-picker-customize" type="text" {{{ inputDataAttr }}} />
			</label>
		</div>
		<?php
	}
}
