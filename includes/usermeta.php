<?php
/**
 * WP Subscriber Management includes: Usermeta class
 *
 * @package WP_Subscriber_Management
 */

namespace WP_Subscriber_Management;

/**
 * Action and filter hooks for working with usermeta.
 *
 * @package WP_Subscriber_Management
 */
class Usermeta {
    /**
     * The usermeta field name of the unsubscribe field.
     *
     * @type string
     */
    const UNSUBSCRIBE_FIELD = 'wp_subscriber_management_unsubscribed';

    /**
     * Renders the unsubscribe field.
     *
     * @param \WP_User $user The user object for the user profile being edited.
     */
    public static function field_unsubscribe( \WP_User $user ) {
        $unsubscribed = (bool) get_user_meta(
            $user->ID,
            self::UNSUBSCRIBE_FIELD,
            true
        );
        require dirname( __DIR__ ) . '/template_parts/field_unsubscribe.php';
    }

    /**
     * Gets the current value of the unsubscribe field for a user ID.
     *
     * @param int $user_id The user ID to use when looking up the value.
     *
     * @return bool True if the user is unsubscribed, false otherwise.
     */
    public static function get_unsubscribe( int $user_id ) : bool {
        return (bool) get_user_meta(
            $user_id,
            self::UNSUBSCRIBE_FIELD,
            true
        );
    }

    /**
     * Saves the unsubscribe field.
     *
     * @param int $user_id The ID of the user profile being edited.
     *
     * @return bool True if save was successful, false otherwise.
     */
    public static function save_unsubscribe( int $user_id ) : bool {
        return self::set_unsubscribe(
            $user_id,
            isset( $_POST['wp-subscriber-management-unsubscribe'] )
        );
    }

    /**
     * Sets the value of the unsubscribe field for a user ID.
     *
     * @param int  $user_id The user ID to use when looking up the value.
     * @param bool $value   The value of the option. True if unsubscribed, false if not.
     *
     * @return bool True if successful, false if not.
     */
    public static function set_unsubscribe( int $user_id, bool $value ) : bool {
        // Permissions check.
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }

        // If the current value is the same as the new value, don't do anything.
        if ( $value === self::get_unsubscribe( $user_id ) ) {
            return true;
        }

        // Switch for new value.
        if ( true === $value ) {
            add_user_meta(
                $user_id,
                self::UNSUBSCRIBE_FIELD,
                true
            );
        } else {
            delete_user_meta(
                $user_id,
                self::UNSUBSCRIBE_FIELD
            );
        }
    }

    /**
     * Initializes functionality by setting up action and filter hooks.
     */
    public static function init() {
        add_action(
            'edit_user_profile',
            [ self::class, 'field_unsubscribe' ]
        );
        add_action(
            'edit_user_profile_update',
            [ self::class, 'save_unsubscribe' ]
        );
        add_action(
            'personal_options_update',
            [ self::class, 'save_unsubscribe' ]
        );
        add_action(
            'show_user_profile',
            [ self::class, 'field_unsubscribe' ]
        );
    }
}
