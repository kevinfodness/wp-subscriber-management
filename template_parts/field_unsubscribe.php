<?php
/**
 * WP_Subscriber_Management Template Parts: Unsubscribe Field
 *
 * Renders a checkbox to control whether the user has unsubscribed from email
 * notifications of new posts.
 *
 * @pacakge WP_Subscriber_Management
 */

?>

<div>
    <label for="wp-subscriber-management-unsubscribe">
        <input
            <?php checked( true, $unsubscribed ); ?>
            id="wp-subscriber-management-unsubscribe"
            name="wp-subscriber-management-unsubscribe"
            type="checkbox"
        />
        <?php
            esc_html_e(
                'Unsubscribe from Email Notifications',
                'wp-subscriber-management'
            );
        ?>
    </label>
</div>