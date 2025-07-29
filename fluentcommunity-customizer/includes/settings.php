<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Add settings page to WP admin.
 */
add_action('admin_menu', 'fcom_add_settings_page');
function fcom_add_settings_page() {
    add_options_page(
        'FluentCommunity Settings',
        'FluentCommunity',
        'manage_options',
        'fcom-settings',
        'fcom_render_settings_page'
    );
}

/**
 * Register settings and sections.
 */
add_action('admin_init', 'fcom_register_settings');
function fcom_register_settings() {
    register_setting('fcom_settings_group', 'fcom_enable_user_defaults');
    register_setting('fcom_settings_group', 'fcom_enable_space_sync');

    add_settings_section(
        'fcom_main_section',
        'Plugin Features',
        function() {
            echo '<p>Toggle each feature on or off below:</p>';
        },
        'fcom-settings'
    );

    add_settings_field(
        'fcom_enable_user_defaults',
        'Default Notification Settings for New Users',
        'fcom_render_checkbox_user_defaults',
        'fcom-settings',
        'fcom_main_section'
    );

    add_settings_field(
        'fcom_enable_space_sync',
        'Auto-Sync Notification Settings When New Spaces Are Created',
        'fcom_render_checkbox_space_sync',
        'fcom-settings',
        'fcom_main_section'
    );
}
add_settings_section(
    'fcom_manual_section',
    'Manual Tools',
    null,
    'fcom-settings'
);
/**
 * Render checkbox: Enable default prefs for new users.
 */
function fcom_render_checkbox_user_defaults() {
    $value = get_option('fcom_enable_user_defaults', '1');
    echo '<input type="checkbox" name="fcom_enable_user_defaults" value="1" ' . checked(1, $value, false) . '> Enable';
}

/**
 * Render checkbox: Enable space sync.
 */
function fcom_render_checkbox_space_sync() {
    $value = get_option('fcom_enable_space_sync', '1');
    echo '<input type="checkbox" name="fcom_enable_space_sync" value="1" ' . checked(1, $value, false) . '> Enable';
}

/**
 * Render the settings page.
 */
function fcom_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>FluentCommunity Customizer</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('fcom_settings_group');
                do_settings_sections('fcom-settings');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}
