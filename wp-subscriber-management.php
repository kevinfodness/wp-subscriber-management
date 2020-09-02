<?php
/**
 * The entry point for the WP Subscriber Management plugin.
 *
 * Plugin Name: Subscriber Management
 * Plugin URI:  https://github.com/kevinfodness/wp-subscriber-management
 * Description: Subscriber management for WordPress.
 * Version:     1.0.0
 * Author:      Kevin Fodness
 * Author URI:  https://github.com/kevinfodness
 *
 * @package WP_Subscriber_Management
 */

namespace WP_Subscriber_Management;

// Load files.
require_once __DIR__ . '/includes/cron.php';
require_once __DIR__ . '/includes/options.php';
require_once __DIR__ . '/includes/partials.php';
require_once __DIR__ . '/includes/usermeta.php';

// Initialize functionality.
Cron::init();
Options::init();
Usermeta::init();
