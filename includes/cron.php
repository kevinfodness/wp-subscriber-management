<?php
/**
 * WP Subscriber Management includes: Cron class
 *
 * @package WP_Subscriber_Management
 */

namespace WP_Subscriber_Management;

/**
 * Handles asynchronous actions via WP cron.
 *
 * @package WP_Subscriber_Management
 */
class Cron {
	/**
	 * Schedules a cron event to trigger the mailer if the circumstances are right.
	 *
	 * @param string $new_status The new post status.
	 * @param string $old_status The old post status.
	 * @param \WP_Post $post The post object that is transitioning.
	 */
	public static function action_transition_post_status( string $new_status, string $old_status, \WP_Post $post ) {
		// If the new status is not `publish`, bail.
		if ( 'publish' !== $new_status ) {
			return;
		}

		// If the new status and the old status are the same, bail.
		if ( $new_status === $old_status ) {
			return;
		}

		// Ensure the post type is one of the allowed types.
		$allowed_types = get_option( 'wp_subscriber_management_post_types', [] );
		if ( ! in_array( $post->post_type, $allowed_types, true ) ) {
			return;
		}

		// Schedule the cron job.
		wp_schedule_single_event(
			time(),
			'wp_subscriber_management_send_email',
			[ $post->ID ]
		);
	}

	/**
	 * Initializes functionality by setting up action and filter hooks.
	 */
	public static function init() {
		add_action(
			'transition_post_status',
			[ self::class, 'action_transition_post_status' ],
			10,
			3
		);
		add_action(
			'wp_subscriber_management_send_email',
			[ self::class, 'send_email' ]
		);
	}

	/**
	 * Sends an email to subscribers with the contents of the given post ID.
	 *
	 * @param int $post_id The ID of the post for which to send the email.
	 */
	public static function send_email( int $post_id ) {
		// Ensure that the notification hasn't already been sent for the post.
		if ( get_post_meta( $post_id, 'wp_subscriber_management_notification_sent', true ) ) {
			return;
		}

		// Add postmeta indicating that the notification has been sent to prevent double emails.
		update_post_meta( $post_id, 'wp_subscriber_management_notification_sent', true );

		// Get a list of users that haven't unsubscribed.
		$users = get_users(
			[
				'meta_query' => [
					[
						'compare' => 'NOT EXISTS',
						'key'     => Usermeta::UNSUBSCRIBE_FIELD,
					],
				],
			]
		);

		// Whittle down the list of users to just their email addresses.
		$emails = array_map(
			function ( \WP_User $user ) {
				return $user->user_email;
			},
			$users
		);

		// Get post content.
		$post = get_post( $post_id );

		// Build the view online link.
		$view_online = sprintf(
		// translators: opening and closing anchor tags.
			esc_html__(
				'Trouble viewing this email? Want to leave a comment? %sView it in a browser%s.',
				'wp-subscriber-management'
			),
			'<a href="' . get_permalink( $post ) . '">',
			'</a>'
		);

		// Build the unsubscribe link with a placeholder for the user's email.
		$unsubscribe = sprintf(
		// translators: opening and closing anchor tags.
			esc_html__(
				'No longer want to receive these notifications? %sUnsubscribe from future updates%s.',
				'wp-subscriber-management'
			),
			'<a href="' . get_permalink( get_option( 'wp_subscriber_management_unsubscribe_page' ) ) . '?unsubscribe=__EMAIL__">',
			'</a>'
		);

		// Extract the post title.
		$post_title = esc_html( $post->post_title );

		// Compile the post content.
		$post_content = apply_filters( 'the_content', $post->post_content );

		// Compile the email.
		$message = <<<HTML
<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>{$post_title}</title>
	</head>
	<body>
		<p>{$view_online}</p>
		{$post_content}
		<p>{$unsubscribe}</p>
	</body>
</html>
HTML;

		// Loop emails and send each.
		foreach ( $emails as $email ) {
			wp_mail(
				$email,
				$post_title,
				str_replace( '__EMAIL__', urlencode( $email ), $message )
			);
		}
	}
}
