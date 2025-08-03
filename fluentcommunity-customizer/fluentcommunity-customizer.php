<?php
/**
 * Plugin Name: FluentCommunity Customizer
 * Description: Manage FluentCommunity user preferences and syncs with a UI dashboard.
 * Version: 1.0.0
 * Author: Snow Media Group LLC
 * License: GPL2
 */

defined('ABSPATH') || exit;

// Load settings and notification logic
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/notifications/notifications.php';
require_once plugin_dir_path(__FILE__) . 'includes/downgrade/downgrade-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/logger.php';
