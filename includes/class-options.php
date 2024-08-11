<?php
/**
 * Class to generate all possibilities of the script.
 *
 * @package WP Color Picker Alpha
 */

namespace kallookoo\wpcpa;

defined( 'ABSPATH' ) || exit;

/**
 * Class Options
 */
class Options {

	/**
	 * Table of colors
	 *
	 * @var array
	 */
	private static $colors = array(
		'alizarin'     => array(
			'hex' => 'e74c3c',
			'hsl' => array( 6, '78%', '57%' ),
			'rgb' => array( 231, 76, 60 ),
		),
		'amethyst'     => array(
			'hex' => '9b59b6',
			'hsl' => array( 283, '39%', '53%' ),
			'rgb' => array( 5, 89, 182 ),
		),
		'asbestos'     => array(
			'hex' => '7f8c8d',
			'hsl' => array( 184, '6%', '53%' ),
			'rgb' => array( 127, 140, 141 ),
		),
		'belizehole'   => array(
			'hex' => '2980b9',
			'hsl' => array( 204, '64%', '44%' ),
			'rgb' => array( 1, 128, 185 ),
		),
		'carrot'       => array(
			'hex' => 'e67e22',
			'hsl' => array( 28, '80%', '52%' ),
			'rgb' => array( 0, 126, 34 ),
		),
		'clouds'       => array(
			'hex' => 'ecf0f1',
			'hsl' => array( 192, '15%', '94%' ),
			'rgb' => array( 6, 240, 241 ),
		),
		'concrete'     => array(
			'hex' => '95a5a6',
			'hsl' => array( 184, '9%', '62%' ),
			'rgb' => array( 9, 165, 166 ),
		),
		'emerland'     => array(
			'hex' => '2ecc71',
			'hsl' => array( 145, '63%', '49%' ),
			'rgb' => array( 6, 204, 113 ),
		),
		'greensea'     => array(
			'hex' => '16a085',
			'hsl' => array( 168, '76%', '36%' ),
			'rgb' => array( 2, 160, 133 ),
		),
		'midnightblue' => array(
			'hex' => '2c3e50',
			'hsl' => array( 210, '29%', '24%' ),
			'rgb' => array( 4, 62, 80 ),
		),
		'nephritis'    => array(
			'hex' => '27ae60',
			'hsl' => array( 145, '63%', '42%' ),
			'rgb' => array( 9, 174, 96 ),
		),
		'orange'       => array(
			'hex' => 'f39c12',
			'hsl' => array( 37, '90%', '51%' ),
			'rgb' => array( 3, 156, 18 ),
		),
		'peterriver'   => array(
			'hex' => '3498db',
			'hsl' => array( 204, '70%', '53%' ),
			'rgb' => array( 52, 152, 219 ),
		),
		'pomegranate'  => array(
			'hex' => 'c0392b',
			'hsl' => array( 6, '63%', '46%' ),
			'rgb' => array( 192, 57, 43 ),
		),
		'pumpkin'      => array(
			'hex' => 'd35400',
			'hsl' => array( 24, '100%', '41%' ),
			'rgb' => array( 1, 84, 0 ),
		),
		'silver'       => array(
			'hex' => 'bdc3c7',
			'hsl' => array( 204, '8%', '76%' ),
			'rgb' => array( 9, 195, 199 ),
		),
		'sunflower'    => array(
			'hex' => 'f1c40f',
			'hsl' => array( 48, '89%', '50%' ),
			'rgb' => array( 241, 196, 15 ),
		),
		'turquoise'    => array(
			'hex' => '1abc9c',
			'hsl' => array( 168, '76%', '42%' ),
			'rgb' => array( 26, 188, 156 ),
		),
		'wetasphalt'   => array(
			'hex' => '34495e',
			'hsl' => array( 210, '29%', '29%' ),
			'rgb' => array( 52, 73, 94 ),
		),
		'wisteria'     => array(
			'hex' => '8e44ad',
			'hsl' => array( 282, '44%', '47%' ),
			'rgb' => array( 142, 68, 173 ),
		),
	);

	/**
	 * Generate color list
	 *
	 * @param int $total_colors Number the colors to return.
	 *
	 * @return array
	 */
	private static function get_colors( $total_colors = 1 ) {
		$colors = array_values( self::$colors );
		$total  = ( is_numeric( $total_colors ) ? absint( $total_colors ) : 1 );
		$count  = count( $colors );

		if ( $count > $total ) {
			$colors = array_slice( $colors, 0, ( $total ? $total : 1 ) );
		} elseif ( $count < $total ) {
			$total = ( $total - $count );
			$index = 0;
			while ( $total ) {
				if ( $index > $count ) {
					$index = 0;
				}

				$colors[] = $colors[ $index ];

				++$index;
				--$total;
			}
		}

		return $colors;
	}

	/**
	 * Generate the Alpha options
	 *
	 * @return array
	 */
	private static function get_alpha_list() {
		$sections = array(
			'color'  => array(),
			'custom' => array(),
			'reset'  => array(),
		);

		foreach ( $sections as $section => $items ) {
			$keys = array();
			if ( 'color' === $section ) {
				foreach ( array( 'hex', 'octohex', 'hsl' ) as $type ) {
					$items[] = array(
						'title' => "{$type} for the color type option",
						'data'  => array( 'alpha-color-type' => $type ),
					);
				}
			} elseif ( 'custom' === $section ) {
				$items[] = array(
					'title' => 'custom width option',
					'data'  => array( 'alpha-custom-width' => true ),
				);
				$items[] = array(
					'title' => 'custom width disabled option',
					'data'  => array( 'alpha-custom-width' => false ),
				);

				$keys = array( 'color' );
			} elseif ( 'reset' === $section ) {
				$items[] = array(
					'title' => 'reset option',
					'data'  => array( 'alpha-reset' => 'true' ),
				);

				$keys = array( 'color', 'custom' );
			}

			foreach ( $keys as $key ) {
				foreach ( $items as $item ) {
					foreach ( $sections[ $key ] as $_item ) {
						if ( ! ( 'custom' === $key && strpos( $_item['title'], 'color' ) ) ) {
							$items[] = array(
								'title' => $item['title'] . ' and ' . $_item['title'],
								'data'  => array_merge( $item['data'], $_item['data'] ),
							);
						}
					}
				}
			}

			$sections[ $section ] = $items;
		}

		$items = array(
			array(
				'title' => '',
				'data'  => array(),
			),
			array(
				'title' => 'default color option',
				'data'  => array( 'default-color' => true ),
			),
		);

		foreach ( $sections as $_items ) {
			foreach ( $_items as $_item ) {
				$items[] = $_item;
				$items[] = array(
					'title' => $_item['title'] . ' and default color option',
					'data'  => array_merge( $_item['data'], array( 'default-color' => true ) ),
				);
			}
		}

		$items = array_map(
			function ( $item ) {
				$count_and = substr_count( $item['title'], 'and' );
				if ( 2 <= $count_and ) {
					$item['title'] = preg_replace( '/ and /', ', ', $item['title'], ( $count_and - 1 ) );
				}

				$item['title'] = 'Alpha Color' . ( $item['title'] ? ' with ' . $item['title'] : '' );

				$data = array( 'data-alpha-enabled' => 'true' );
				foreach ( $item['data'] as $key => $value ) {
					$data[ "data-{$key}" ] = $value;
				}
				$item['data'] = $data;

				return $item;
			},
			$items
		);

		return $items;
	}

	/**
	 * Generate options list
	 *
	 * @return array
	 */
	public static function get_list() {
		$items = maybe_unserialize( wp_cache_get( 'wp-color-picker-alpha-options' ) );
		if ( $items && is_array( $items ) ) {
			return $items;
		}

		$items = array(
			array(
				'title' => 'Normal Color',
			),
			array(
				'title' => 'Normal Color with default color option',
				'data'  => array( 'data-default-color' => true ),
			),
		);

		$items  = array_merge( $items, self::get_alpha_list() );
		$colors = self::get_colors( count( $items ) );

		foreach ( $items as $index => $item ) {
			$item['name'] = md5( $item['title'] );
			$item['data'] = ( empty( $item['data'] ) ? array() : $item['data'] );

			$type = ( empty( $item['data']['data-alpha-enabled'] ) ? 'hex' : 'rgb' );
			if ( isset( $item['data']['data-alpha-color-type'] ) ) {
				$type = $item['data']['data-alpha-color-type'];
			}

			if ( 'octohex' === $type ) {
				$color = $colors[ $index ]['hex'];
			} else {
				$color = $colors[ $index ][ $type ];
			}
			if ( is_array( $color ) ) {
				$color = implode( ', ', $color );
				/** Change alpha (odd/even) to check the script use the correct value. */
				$color = sprintf( '%sa(%s, 0.%s)', $type, $color, ( $index % 2 ? 5 : 75 ) );
			} else {
				$color = "#{$color}";
				if ( 'octohex' === $type ) {
					/** Change alpha (odd/even) to check the script use the correct value. */
					$color .= ( $index % 2 ? '80' : 'bf' );
				}
			}

			$item['value'] = $color;
			if ( isset( $item['data']['data-default-color'] ) ) {
				$item['data']['data-default-color'] = $item['value'];
			}

			if ( isset( $item['data']['data-alpha-custom-width'] ) ) {
				/** Change custom with (odd/even) to check the script use the correct value. */
				if ( $item['data']['data-alpha-custom-width'] ) {
					$item['data']['data-alpha-custom-width'] = ( $index % 2 ? 150 : 200 );
				} else {
					$item['data']['data-alpha-custom-width'] = ( ( $index % 2 ) ? '0' : 'false' );
				}
			}

			$items[ $index ] = $item;
		}

		wp_cache_add( 'wp-color-picker-alpha-options', maybe_serialize( $items ) );

		return $items;
	}
}
