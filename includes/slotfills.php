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
        if ( file_exists( dirname( __DIR__ ) . '/build/skip-publish.asset.php' ) ) {
            $asset_details = include dirname( __DIR__ ) . '/build/skip-publish.asset.php';
            wp_enqueue_script(
                'wp-subscriber-management-slotfills',
                plugins_url( 'build/skip-publish.js', __DIR__ ),
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
	}
}
