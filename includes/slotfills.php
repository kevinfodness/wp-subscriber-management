<?php
/**
 * WP Subscriber Management includes: Slotfills class
 *
 * @package WP_Subscriber_Management
 */

namespace WP_Subscriber_Management;

/**
 * Enqueues slotfill scripts and registers postmeta managed by slotfills.
 *
 * @package WP_Subscriber_Management
 */
class Slotfills {
    /**
     * A callback function for the enqueue_block_editor_assets action hook.
     */
    public static function action__enqueue_block_editor_assets() {
		// Ensure this post type supports subscriber manageemnt.
		$post_type  = get_post_type();
		$post_types = get_option( 'wp_subscriber_management_post_types', [] );
		if ( ! is_array( $post_types ) || ! in_array( $post_type, $post_types, true ) ) {
			return;
		}

		// Enqueue the slotfill JS if it is built.
        if ( file_exists( dirname( __DIR__ ) . '/build/slotfills.tsx.asset.php' ) ) {
            $asset_details = include dirname( __DIR__ ) . '/build/slotfills.tsx.asset.php';
            wp_enqueue_script(
                'wp-subscriber-management-slotfills',
                plugins_url( 'build/slotfills.tsx.js', __DIR__ ),
                $asset_details['dependencies'] ?? [],
                $asset_details['version'] ?? false,
                true
            );
        }
    }

	/**
	 * Initializes functionality by setting up action and filter hooks.
	 */
	public static function init() {
		add_action(
			'enqueue_block_editor_assets',
			[ self::class, 'action__enqueue_block_editor_assets' ]
		);

		// Register custom metadata for affected post types.
		$post_types = get_option( 'wp_subscriber_management_post_types', [] );
		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				register_post_meta(
					$post_type,
					'wp_subscriber_management_skip_push',
					[
						'default'      => false,
						'show_in_rest' => true,
						'single'       => true,
						'type'         => 'boolean',
					]
				);
			}
		}
	}
}
