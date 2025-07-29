<?php
/**
 * Plugin Name: FluentCommunity Customizer
 * Description: Manage FluentCommunity user preferences and syncs with a UI dashboard.
 * Version: 1.0.1
 * Author: Snow Media Group LLC
 * License: GPL2
 */
require_once plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php';

$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/BeardedITDad/Fluentcommunity', // Your repo URL
    __FILE__,
    'fluentcommunity-customizer' // Plugin slug
);
defined('ABSPATH') || exit;

// Load settings page
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/notifications.php';
