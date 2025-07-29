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
            <?php if (isset($_GET['notifications']) && $_GET['notifications'] === 'success') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>Notifications applied to all existing users.</p>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('fcom_apply_notifications'); ?>
                <input type="hidden" name="action" value="fcom_apply_notifications_now">
                <?php submit_button('Apply Default Notifications to All Users'); ?>
        </form>

    </div>
    <?php
}
