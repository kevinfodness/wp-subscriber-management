<?php
/**
 * WP Subscriber Management includes: Options class
 *
 * @package WP_Subscriber_Management
 */

namespace WP_Subscriber_Management;

/**
 * Options page for WP Subscriber Management.
 *
 * @package WP_Subscriber_Management
 */
class Options {
	/**
	 * Registers settings fields.
	 */
	public static function action_admin_init() {
		// Define fields to register.
		$fields = [
			'wp_subscriber_management_post_types' => [
				'label'    => esc_html__( 'Post Types', 'wp-subscriber-management' ),
				'sanitize' => [ self::class, 'sanitize_post_types' ],
			],
			'wp_subscriber_management_unsubscribe_page' => [
				'label'    => esc_html__( 'Unsubscribe Page', 'wp-subscriber-management' ),
				'sanitize' => 'absint',
			],
		];

		// Register the config section.
		add_settings_section(
			'wp_subscriber_management_config_section',
			esc_html__( 'Subscriber Management Configuration', 'wp-subscriber-management' ),
			null,
			'wp_subscriber_management_config_options'
		);

		// Loop over field definitions and register each.
		foreach ( $fields as $field_key => $field_properties ) {

			// Add the definition for the field.
			add_settings_field(
				$field_key,
				$field_properties['label'],
				[ self::class, 'render_field' ],
				'wp_subscriber_management_config_options',
				'wp_subscriber_management_config_section',
				[
					'field_name' => $field_key,
					'label_for'  => str_replace( '_', '-', $field_key ),
				]
			);

			// Register the fields.
			register_setting(
				'wp_subscriber_management_config_section',
				$field_key,
				$field_properties['sanitize']
			);
		}
	}

	/**
	 * Adds the options page to the Settings menu.
	 */
	public static function action_admin_menu() {
		add_submenu_page(
			'options-general.php',
			esc_html__( 'Subscriber Management', 'wp-subscriber-management' ),
			esc_html__( 'Subscriptions', 'wp-subscriber-management' ),
			'manage_options',
			'wp-subscriber-management',
			[ self::class, 'render_options_page' ]
		);
	}

	/**
	 * Initializes functionality by setting up action and filter hooks.
	 */
	public static function init() {
		add_action(
			'admin_init',
			[ self::class, 'action_admin_init' ]
		);
		add_action(
			'admin_menu',
			[ self::class, 'action_admin_menu' ]
		);
	}

	/**
	 * Renders a field for an option.
	 *
	 * @param array $args Field arguments.
	 */
	public static function render_field( array $args ) {

		// Get the un-namespaced field name.
		$field_name = str_replace( 'wp_subscriber_management_', '', $args['field_name'] );

		// Get the current value for the option.
		$args['value'] = get_option( $args['field_name'] );

		// Prepare data for partials based on field name.
		switch ( $field_name ) {
			case 'post_types':
				$args['options'] = self::supported_post_types();
				$args['value']   = empty( $args['value'] ) ? [] : $args['value'];
				break;
			case 'unsubscribe_page':
				$args['options'] = get_pages();
				$args['value']   = empty( $args['value'] ) ? 0 : (int) $args['value'];
				break;
		}

		// Load the field partial.
		Partials::load(
			'field_' . $field_name,
			$args
		);
	}

	/**
	 * Renders the options page.
	 */
	public static function render_options_page() {
		Partials::load( 'options_page' );
	}

	/**
	 * Sanitizes an array of post type slugs.
	 *
	 * @param array $option_value The array of post type slugs to sanitize.
	 *
	 * @return array The sanitized array.
	 */
	public static function sanitize_post_types( array $option_value ) : array {
		return array_map( 'sanitize_text_field', $option_value );
	}

	/**
	 * Gets an array of possible supported post types.
	 *
	 * @return array An array of post type objects.
	 */
	public static function supported_post_types() {

		// Get a list of public post types minus attachments and Gutenberg blocks.
		$post_types = get_post_types(
			[
				'show_ui' => true,
			],
			'objects'
		);
		unset( $post_types['attachment'] );
		unset( $post_types['wp_block'] );

		return $post_types;
	}
}
