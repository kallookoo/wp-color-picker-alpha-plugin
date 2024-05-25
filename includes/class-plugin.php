<?php
/**
 * Main Class for testing inside the Customize.
 *
 * @package WP Color Picker Alpha
 */

namespace kallookoo\wpcpa;

use kallookoo\wpcpa\Customize\Alpha_Color_Control;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin
 */
class Plugin {

	/**
	 * Action after_setup_theme
	 */
	public static function after_setup_theme() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 10 );
		add_action( 'customize_register', array( __CLASS__, 'customize_register' ), 10 );
		add_action( 'widgets_init', array( __CLASS__, 'widgets_init' ), 10 );

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 10 );
			add_action( 'admin_menu', __NAMESPACE__ . '\Admin\Plugin::admin_menu', PHP_INT_MAX );
		}
	}

	/**
	 * Action wp_enqueue_scripts and admin_enqueue_scripts
	 */
	public static function enqueue_scripts() {
		wp_register_script(
			WP_COLOR_PICKER_ALPHA_SCRIPT_NAME,
			plugins_url( 'assets/js/wp-color-picker-alpha.js', WP_COLOR_PICKER_ALPHA_PLUGIN_FILE ),
			array( 'wp-color-picker' ),
			time(),
			true
		);
	}

	/**
	 * Action customize_register
	 *
	 * @param WP_Customize_Manager $wp_customize Manager instance.
	 */
	public static function customize_register( $wp_customize ) {
		/** Register a customize control type. */
		$wp_customize->register_control_type( __NAMESPACE__ . '\Customize\Alpha_Color_Control' );
		/** Add a customize section. */
		$wp_customize->add_section(
			'wp_color_picker_alpha',
			array(
				'title'    => 'Color Picker Alpha',
				'priority' => -100, /** Ensure this section add to first sections. */
			)
		);
		/** Add a controls with all possibilities of the script. */
		foreach ( Options::get_list() as $index => $option ) {
			/** Change the name for use some name option. */
			$name = sprintf( 'wp-color-picker-alpha-customize[%s]', $option['name'] );
			/** Create the input attributes. */
			$input_attrs = array_merge( array( 'name' => $name ), $option['data'] );

			if ( ! empty( $option['value'] ) ) {
				$input_attrs['value'] = $option['value'];
			}

			$setting_args = array(
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
				'type'              => 'option',
			);

			/** If exists the default color add to the setting. */
			if ( ! empty( $option['data']['data-default-color'] ) ) {
				$setting_args['default'] = $option['data']['data-default-color'];
			}

			/** Add customize setting */
			$wp_customize->add_setting( $name, $setting_args );
			/** Add customize control */
			$wp_customize->add_control(
				new Alpha_Color_Control(
					$wp_customize,
					$name,
					array(
						'label'       => 'Example #' . ( $index + 1 ),
						'description' => $option['title'],
						'section'     => 'wp_color_picker_alpha',
						'input_attrs' => $input_attrs,
					)
				)
			);
		}
	}

	/**
	 * Action widgets_init
	 */
	public static function widgets_init() {
		register_widget( __NAMESPACE__ . '\Widget' );
	}
}
