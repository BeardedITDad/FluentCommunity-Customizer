<?php
/**
 * Plugin Name: FluentCommunity Customizer
 * Description: Manage FluentCommunity user preferences and syncs with a UI dashboard.
 * Version: 1.0.2
 * Author: Snow Media Group LLC
 * License: GPL2
 */
defined('ABSPATH') || exit;

// Load settings and notification logic
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/notifications.php';

// Delay loading the update checker until plugins are ready
add_action('plugins_loaded', function () {
    $update_checker_path = plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php';

    if (file_exists($update_checker_path)) {
        require_once $update_checker_path;

        if (class_exists('Puc_v4_Factory')) {
            $updateChecker = Puc_v4_Factory::buildUpdateChecker(
                'https://github.com/BeardedITDad/Fluentcommunity',
                __FILE__,
                'fluentcommunity-customizer'
            );
        } else {
            error_log('❌ Plugin Update Checker: class not found even after loading.');
        }
    } else {
        error_log('❌ Plugin Update Checker: file not found at ' . $update_checker_path);
    }
});

