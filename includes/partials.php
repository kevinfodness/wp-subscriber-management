<?php
/**
 * WP Subscriber Management includes: Partials class
 *
 * @package WP_Subscriber_Management
 */

namespace WP_Subscriber_Management;

/**
 * Partials helper class.
 *
 * @package WP_Subscriber_Management
 */
class Partials {

	/**
	 * A helper function for loading partials.
	 *
	 * @param string $slug The partial filepath to the partial template.
	 * @param array  $args Optional. Arguments to pass to the template part.
	 */
	public static function load( string $slug, array $args = [] ) {

		// Ensure requested partial exists.
		$filepath = dirname( __DIR__ ) . '/template_parts/' . $slug . '.php';
		if ( ! file_exists( $filepath ) ) {
			return;
		}

		require $filepath;
	}
}
