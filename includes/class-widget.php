<?php
/**
 * Widget Class for testing all possibilities.
 *
 * @package WP Color Picker Alpha
 */

namespace kallookoo\wpcpa;

use \WP_Widget;

defined( 'ABSPATH' ) || exit;

/**
 * Class Widget
 */
class Widget extends WP_Widget {

	/**
	 * Whether or not the widget has been registered yet.
	 *
	 * @var bool
	 */
	protected $registered = false;

	/**
	 * Fields
	 *
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Sets up a new widget instance.
	 */
	public function __construct() {
		parent::__construct(
			'color-picker-alpha',
			'Color Picker Alpha',
			[
				'classname'                   => 'widget_color_picker_alpha',
				'description'                 => 'Testing Color Picker Alpha',
				'customize_selective_refresh' => true,
			],
			[
				'width'  => 400,
				'height' => 350,
			]
		);

		// Generate fields template.
		if ( empty( $this->fields ) ) {
			foreach ( Options::get_list() as $index => $option ) {
				$this->fields[ $index ] = wp_parse_args(
					$option['data'],
					[
						'id'    => $option['name'],
						'name'  => $option['name'],
						'class' => 'color-picker-widget',
						'value' => ( empty( $option['value'] ) ? '' : $option['value'] ),
						'title' => $option['title'],
					]
				);
			}
		}
	}

	/**
	 * Add hooks for enqueueing assets when registering all widget instances of this widget class.
	 *
	 * @param integer $number Optional. The unique order number of this widget instance
	 *                        compared to other instances of the same class. Default -1.
	 */
	public function _register_one( $number = -1 ) { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		parent::_register_one( $number );
		if ( ! $this->registered ) {
			// Note that the widgets component in the customizer will also do
			// the 'admin_print_scripts-widgets.php' action in WP_Customize_Widgets::print_scripts().
			add_action( 'admin_print_scripts-widgets.php', [ $this, 'enqueue_admin_scripts' ] );
			$this->registered = true;
		}
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		?>
		<div style="overflow-y: scroll; max-height: 340px; margin-bottom: 10px;">
		<?php
		$instance = ( is_array( $instance ) ? $instance : [] );
		foreach ( $this->fields as $index => $field ) {
			if ( isset( $instance[ $field['name'] ] ) ) {
				$field['value'] = $instance[ $field['name'] ];
			}
			?>
			<p><strong>Example #<?php echo esc_html( $index + 1 ); ?><hr><?php echo esc_html( $field['title'] ); ?></strong></p>
			<?php
			$output = '';
			foreach ( $field as $name => $value ) {
				if ( 'title' !== $name ) {
					if ( 'id' === $name ) {
						$value = $this->get_field_id( $field['name'] );
					} elseif ( 'name' === $name ) {
						$value = $this->get_field_name( $field['name'] );
					}
					$output .= sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
				}
			}

			?>
			<p><input type="text"<?php echo wp_kses( $output, 'strip' ); ?>></p>
			<?php
		}
		?>
		</div>
		<?php
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		// Empty output this widget only if one example.
	}

	/**
	 * Loads the required scripts and styles for the widget control.
	 *
	 * @since 4.8.0
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script(
			WP_COLOR_PICKER_ALPHA_SCRIPT_NAME . '-widget-color-picker-alpha',
			plugins_url( 'assets/js/widget-color-picker-alpha.js', WP_COLOR_PICKER_ALPHA_PLUGIN_FILE ),
			[ WP_COLOR_PICKER_ALPHA_SCRIPT_NAME ],
			time(),
			true
		);
	}
}
