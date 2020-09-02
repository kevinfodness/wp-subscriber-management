<?php
/**
 * WP_Subscriber_Management Template Parts: Unsubscribe Page Field
 *
 * Renders the unsubscribe page field for the options page.
 *
 * @global array $args Arguments passed to the template part.
 *
 * @package WP_Subscriber_Management
 */

?>

<div>
	<label for="wp-subscriber-management-unsubscribe-page">
		<select id="wp-subscriber-management-unsubscribe-page"
			   name="wp_subscriber_management_unsubscribe_page"
		>
			<option
				<?php selected( 0, $args['value'] ); ?>
				value="0"
			>
				Choose a Page
			</option>
			<?php foreach ( $args['options'] as $option ) : ?>
				<option
					<?php selected( $option->ID, $args['value'] ); ?>
					value="<?php echo esc_attr( $option->ID ); ?>"
				>
					<?php echo esc_html( $option->post_title ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</label>
</div>
<p class="description">
	<?php
		esc_html_e(
			'Select which post types should trigger emails to subscribers on publish.',
			'wp-subscriber-management'
		);
	?>
</p>
