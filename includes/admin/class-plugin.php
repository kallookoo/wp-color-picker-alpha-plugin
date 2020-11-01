<?php
/**
 * Main Class for testing inside the Option page.
 *
 * @package WP Color Picker Alpha
 */

namespace kallookoo\wpcpa\Admin;

use \kallookoo\wpcpa\Options;

defined( 'ABSPATH' ) || exit;

/**
 * Class Admin Plugin
 */
class Plugin {

	/**
	 * Plugin admin page
	 *
	 * @var string
	 */
	private static $admin_page;

	/**
	 * Options
	 *
	 * @var array
	 */
	private static $options;

	/**
	 * Action admin_menu
	 */
	public static function admin_menu() {
		self::$options    = get_option( 'wp-color-picker-alpha', [] );
		self::$admin_page = add_menu_page(
			'Color Picker Alpha',
			'Color Picker A.',
			'manage_options',
			'wp-color-picker-alpha',
			[ __CLASS__, 'add_options_page' ]
		);

		add_action( 'admin_init', [ __CLASS__, 'admin_init' ], 10 );
		add_action( 'load-' . self::$admin_page, [ __CLASS__, 'load_options_page' ], 10 );
	}

	/**
	 * Action admin_init
	 */
	public static function admin_init() {
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_scripts' ], 10 );

		register_setting( 'wp-color-picker-alpha-register', 'wp-color-picker-alpha' );
	}

	/**
	 * Action load-$admin_page
	 */
	public static function load_options_page() {
		add_settings_section( 'wp-color-picker-alpha', 'Examples using the wp-color-picker-alpha script', '', self::$admin_page );

		foreach ( Options::get_list() as $index => $option ) {
			add_settings_field(
				$option['name'],
				'Example #' . ( $index + 1 ),
				[ __CLASS__, 'render_field' ],
				self::$admin_page,
				'wp-color-picker-alpha',
				$option
			);
		}
	}

	/**
	 * Render the setting field
	 *
	 * @param array $args Field arguments.
	 */
	public static function render_field( $args ) {
		if ( isset( $args['name'], self::$options[ $args['name'] ] ) ) {
			$args['value'] = self::$options[ $args['name'] ];
		}

		$attributes = 'name="' . ( isset( $args['name'] ) ? 'wp-color-picker-alpha[' . $args['name'] . ']' : '' ) . '"';
		if ( isset( $args['value'] ) ) {
			$attributes .= ' value="' . $args['value'] . '"';
		}

		if ( isset( $args['data'] ) ) {
			foreach ( $args['data'] as $name => $value ) {
				if ( isset( $value ) ) {
					$attributes .= sprintf( ' %s="%s"', $name, $value );
				}
			}
		}
		?>
		<div class="notice inline">
			<p>
				<strong>
					<?php echo esc_html( $args['title'] ); ?>
						<?php if ( ! empty( $args['desc'] ) ) : ?>
						<span> with <?php echo esc_html( $args['desc'] ); ?></span>
					<?php endif; ?>
				</strong>
			</p>
			<p><input type="text" class="color-picker"<?php echo wp_kses( $attributes, 'strip' ); ?>></p>
		</div>
		<?php
	}

	/**
	 * Action admin_enqueue_scripts
	 *
	 * @param string $admin_page The current admin page.
	 */
	public static function admin_enqueue_scripts( $admin_page ) {
		if ( self::$admin_page === $admin_page ) {
			wp_enqueue_style( 'wp-color-picker' );

			wp_add_inline_script(
				WP_COLOR_PICKER_ALPHA_SCRIPT_NAME,
				'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
			);

			wp_enqueue_script( WP_COLOR_PICKER_ALPHA_SCRIPT_NAME );
		}
	}

	/**
	 * Render the options page
	 */
	public static function add_options_page() {
		?>
		<div class="wrap">
			<h2>WP Color Picker Alpha</h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
				<?php settings_fields( 'wp-color-picker-alpha-register' ); ?>
				<?php do_settings_sections( self::$admin_page ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
