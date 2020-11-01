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
	private static $colors = [
		'alizarin'     => [
			'hex' => 'e74c3c',
			'hsl' => [ 6, '78%', '57%' ],
			'rgb' => [ 231, 76, 60 ],
		],
		'amethyst'     => [
			'hex' => '9b59b6',
			'hsl' => [ 283, '39%', '53%' ],
			'rgb' => [ 5, 89, 182 ],
		],
		'asbestos'     => [
			'hex' => '7f8c8d',
			'hsl' => [ 184, '6%', '53%' ],
			'rgb' => [ 127, 140, 141 ],
		],
		'belizehole'   => [
			'hex' => '2980b9',
			'hsl' => [ 204, '64%', '44%' ],
			'rgb' => [ 1, 128, 185 ],
		],
		'carrot'       => [
			'hex' => 'e67e22',
			'hsl' => [ 28, '80%', '52%' ],
			'rgb' => [ 0, 126, 34 ],
		],
		'clouds'       => [
			'hex' => 'ecf0f1',
			'hsl' => [ 192, '15%', '94%' ],
			'rgb' => [ 6, 240, 241 ],
		],
		'concrete'     => [
			'hex' => '95a5a6',
			'hsl' => [ 184, '9%', '62%' ],
			'rgb' => [ 9, 165, 166 ],
		],
		'emerland'     => [
			'hex' => '2ecc71',
			'hsl' => [ 145, '63%', '49%' ],
			'rgb' => [ 6, 204, 113 ],
		],
		'greensea'     => [
			'hex' => '16a085',
			'hsl' => [ 168, '76%', '36%' ],
			'rgb' => [ 2, 160, 133 ],
		],
		'midnightblue' => [
			'hex' => '2c3e50',
			'hsl' => [ 210, '29%', '24%' ],
			'rgb' => [ 4, 62, 80 ],
		],
		'nephritis'    => [
			'hex' => '27ae60',
			'hsl' => [ 145, '63%', '42%' ],
			'rgb' => [ 9, 174, 96 ],
		],
		'orange'       => [
			'hex' => 'f39c12',
			'hsl' => [ 37, '90%', '51%' ],
			'rgb' => [ 3, 156, 18 ],
		],
		'peterriver'   => [
			'hex' => '3498db',
			'hsl' => [ 204, '70%', '53%' ],
			'rgb' => [ 52, 152, 219 ],
		],
		'pomegranate'  => [
			'hex' => 'c0392b',
			'hsl' => [ 6, '63%', '46%' ],
			'rgb' => [ 192, 57, 43 ],
		],
		'pumpkin'      => [
			'hex' => 'd35400',
			'hsl' => [ 24, '100%', '41%' ],
			'rgb' => [ 1, 84, 0 ],
		],
		'silver'       => [
			'hex' => 'bdc3c7',
			'hsl' => [ 204, '8%', '76%' ],
			'rgb' => [ 9, 195, 199 ],
		],
		'sunflower'    => [
			'hex' => 'f1c40f',
			'hsl' => [ 48, '89%', '50%' ],
			'rgb' => [ 241, 196, 15 ],
		],
		'turquoise'    => [
			'hex' => '1abc9c',
			'hsl' => [ 168, '76%', '42%' ],
			'rgb' => [ 26, 188, 156 ],
		],
		'wetasphalt'   => [
			'hex' => '34495e',
			'hsl' => [ 210, '29%', '29%' ],
			'rgb' => [ 52, 73, 94 ],
		],
		'wisteria'     => [
			'hex' => '8e44ad',
			'hsl' => [ 282, '44%', '47%' ],
			'rgb' => [ 142, 68, 173 ],
		],
	];

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
		$sections = [
			'color'  => [],
			'custom' => [],
			'reset'  => [],
		];

		foreach ( $sections as $section => $items ) {
			$keys = [];
			if ( 'color' === $section ) {
				foreach ( [ 'hex', 'hsl' ] as $type ) {
					$items[] = [
						'title' => "{$type} for the color type option",
						'data'  => [ 'alpha-color-type' => $type ],
					];
				}
			} elseif ( 'custom' === $section ) {
				$items[] = [
					'title' => 'custom width option',
					'data'  => [ 'alpha-custom-width' => true ],
				];
				$items[] = [
					'title' => 'custom width disabled option',
					'data'  => [ 'alpha-custom-width' => false ],
				];

				$keys = [ 'color' ];
			} elseif ( 'reset' === $section ) {
				$items[] = [
					'title' => 'reset option',
					'data'  => [ 'alpha-reset' => 'true' ],
				];

				$keys = [ 'color', 'custom' ];
			}

			foreach ( $keys as $key ) {
				foreach ( $items as $item ) {
					foreach ( $sections[ $key ] as $_item ) {
						if ( ! ( 'custom' === $key && strpos( $_item['title'], 'color' ) ) ) {
							$items[] = [
								'title' => $item['title'] . ' and ' . $_item['title'],
								'data'  => array_merge( $item['data'], $_item['data'] ),
							];
						}
					}
				}
			}

			$sections[ $section ] = $items;
		}

		$items = [
			[
				'title' => '',
				'data'  => [],
			],
			[
				'title' => 'default color option',
				'data'  => [ 'default-color' => true ],
			],
		];

		foreach ( $sections as $_items ) {
			foreach ( $_items as $_item ) {
				$items[] = $_item;
				$items[] = [
					'title' => $_item['title'] . ' and default color option',
					'data'  => array_merge( $_item['data'], [ 'default-color' => true ] ),
				];
			}
		}

		$items = array_map(
			function ( $item ) {
				$count_and = substr_count( $item['title'], 'and' );
				if ( 2 <= $count_and ) {
					$item['title'] = preg_replace( '/ and /', ', ', $item['title'], ( $count_and - 1 ) );
				}

				$item['title'] = 'Alpha Color' . ( $item['title'] ? ' with ' . $item['title'] : '' );

				$data = [ 'data-alpha-enabled' => 'true' ];
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

		$items = [
			[
				'title' => 'Normal Color',
			],
			[
				'title' => 'Normal Color with default color option',
				'data'  => [ 'data-default-color' => true ],
			],
		];

		$items  = array_merge( $items, self::get_alpha_list() );
		$colors = self::get_colors( count( $items ) );

		foreach ( $items as $index => $item ) {
			$item['name'] = md5( $item['title'] );
			$item['data'] = ( empty( $item['data'] ) ? [] : $item['data'] );

			$type = ( empty( $item['data']['data-alpha-enabled'] ) ? 'hex' : 'rgb' );
			if ( isset( $item['data']['data-alpha-color-type'] ) ) {
				$type = $item['data']['data-alpha-color-type'];
			}

			$color = $colors[ $index ][ $type ];
			if ( is_array( $color ) ) {
				$color = implode( ', ', $color );
				/** Change alpha (odd/even) to check the script use the correct value. */
				$color = sprintf( '%sa(%s, 0.%s)', $type, $color, ( $index % 2 ? 5 : 75 ) );
			} else {
				$color = "#{$color}";
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
