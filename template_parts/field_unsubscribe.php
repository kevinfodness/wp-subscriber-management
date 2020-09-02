<?php
/**
 * WP_Subscriber_Management Template Parts: Unsubscribe Field
 *
 * Renders a checkbox to control whether the user has unsubscribed from email
 * notifications of new posts.
 *
 * @global array $args Arguments passed to the template part.
 *
 * @package WP_Subscriber_Management
 */

?>

<h2><?php esc_html_e( 'Unsubscribe', 'wp-subscriber-management' ); ?></h2>
<table class="form-table" role="presentation">
	<tbody>
	<tr id="wp-subscriber-management" class="wp-subscriber-management-wrap">
		<th>
			<label for="wp-subscriber-management-unsubscribe">
				<?php
				esc_html_e(
					'Unsubscribe from Email Notifications',
					'wp-subscriber-management'
				);
				?>
			</label>
		</th>
		<td>
			<input
				<?php checked( true, $args['unsubscribed'] ); ?>
				id="wp-subscriber-management-unsubscribe"
				name="wp-subscriber-management-unsubscribe"
				type="checkbox"
			/>
		</td>
	</tr>
	</tbody>
</table>
