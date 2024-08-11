<?php
/**
 * Plugin Name: WP Color Picker Alpha
 * Plugin URI: https://github.com/kallookoo/wp-color-picker-alpha
 * Description: Plugin to test the wp-color-picker-alpha script, creating an options page, and a new section on the current theme.
 * Version: 3.0.4
 * Author: Sergio ( kallookoo )
 * Author URI: https://dsergio.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package WP Color Picker Alpha
 */

namespace kallookoo\wpcpa;

defined( 'ABSPATH' ) || exit;

spl_autoload_register(
	function ( $item ) {
		if ( 0 === strncmp( __NAMESPACE__, $item, 15 ) ) {
			$item = str_replace( array( '\\', '_' ), array( '/', '-' ), strtolower( substr( $item, 15 ) ) );
			$item = preg_replace( '@([^/]+)$@', 'class-$1.php', $item );

			if ( file_exists( __DIR__ . '/includes' . $item ) ) {
				require_once __DIR__ . '/includes' . $item;
			}
		}
	}
);

define( 'WP_COLOR_PICKER_ALPHA_PLUGIN_FILE', __FILE__ );
// Random name to force for check the version of the script.
define( 'WP_COLOR_PICKER_ALPHA_SCRIPT_NAME', md5( __FILE__ ) . time() );

add_action( 'after_setup_theme', __NAMESPACE__ . '\Plugin::after_setup_theme', PHP_INT_MAX );

/**
 * Remove all plugin data when deactivated.
 *
 * @since 3.0.0
 */
function on_deactivation() {
	// Option page.
	delete_option( 'wp-color-picker-alpha' );
	// Customize.
	delete_option( 'wp-color-picker-alpha-customize' );
	// Widget.
	delete_option( 'widget_color-picker-alpha' );
	// Cache of the list of the fields.
	wp_cache_delete( 'wp-color-picker-alpha-options' );
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\on_deactivation' );
