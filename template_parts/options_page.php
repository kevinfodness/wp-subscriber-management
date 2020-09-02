<?php
/**
 * WP_Subscriber_Management Template Parts: Options Page
 *
 * Renders the options page for the plugin.
 *
 * @global array $args Arguments passed to the template part.
 *
 * @package WP_Subscriber_Management
 */

?>

<div class="wrap">
	<h1><?php esc_html_e( 'Subscriber Management', 'wp-subscriber-management' ); ?></h1>
	<?php settings_errors(); ?>
	<form id="wp-subscriber-management-config" method="post" action="options.php">
		<?php settings_fields( 'wp_subscriber_management_config_section' ); ?>
		<?php do_settings_sections( 'wp_subscriber_management_config_options' ); ?>
		<?php submit_button(); ?>
	</form>
</div>
