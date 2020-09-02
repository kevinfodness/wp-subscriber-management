<?php
/**
 * WP_Subscriber_Management Template Parts: Post Types Field
 *
 * Renders the post types field for the options page.
 *
 * @global array $args Arguments passed to the template part.
 *
 * @package WP_Subscriber_Management
 */

?>

<fieldset>
	<legend class="screen-reader-text">
		<?php esc_html_e( 'Post Types', 'wp-subscriber-management' ); ?>
	</legend>
	<?php foreach ( $args['options'] as $post_type ) : ?>
		<div>
			<label for="wp-subscriber-management-post-types-<?php echo esc_attr( $post_type->name ); ?>">
				<input id="wp-subscriber-management-post-types-<?php echo esc_attr( $post_type->name ); ?>"
				       name="wp_subscriber_management_post_types[]"
				       type="checkbox"
				       value="<?php echo esc_attr( $post_type->name ); ?>"
					<?php checked( in_array( $post_type->name, $args['value'], true ) ); ?>
				/>
				<?php echo esc_html( $post_type->label ); ?>
			</label>
		</div>
	<?php endforeach; ?>
</fieldset>
<p class="description">
	<?php
		esc_html_e(
			'Select which post types should trigger emails to subscribers on publish.',
			'wp-subscriber-management'
		);
	?>
</p>
