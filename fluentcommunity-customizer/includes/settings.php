<?php

// Add the admin menu item
function fcc_register_settings_page() {
    add_menu_page(
        'FluentCommunity Settings',
        'FluentCommunity',
        'manage_options',
        'fluentcommunity-settings',
        'fcc_render_settings_page',
        'dashicons-admin-generic',
        80
    );
}
add_action('admin_menu', 'fcc_register_settings_page');

// Render the settings page
function fcc_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>FluentCommunity Settings</h1>

        <form method="post" action="options.php">
            <?php
            settings_fields('fcc_settings_group');
            do_settings_sections('fluentcommunity-settings');
            submit_button();
            ?>
        </form>

        <hr>
        <h2>Plugin Logs</h2>
        <p>These logs show actions taken by the plugin, such as auto-applying settings to users or handling new spaces.</p>

        <?php
        $log_file = plugin_dir_path(__DIR__) . '../fluentcommunity.log';

        // Clear log if requested and nonce verified
        if (
            isset($_POST['fcc_clear_log']) &&
            check_admin_referer('fcc_clear_log_action', 'fcc_clear_log_nonce')
        ) {
            fcc_clear_log();
            echo '<div class="updated"><p>Log cleared.</p></div>';
        }

        // Load and truncate log (optional safety limit)
        $log_contents = fcc_get_log_contents();
        $max_chars = 10000;
        if (strlen($log_contents) > $max_chars) {
            $log_contents = substr($log_contents, -$max_chars);
            $log_contents = "[... truncated ...]\n" . $log_contents;
        }
        ?>

        <form method="post">
            <?php wp_nonce_field('fcc_clear_log_action', 'fcc_clear_log_nonce'); ?>
            <textarea readonly rows="15" style="width:100%; font-family:monospace;"><?php echo esc_textarea($log_contents); ?></textarea>
            <p>
                <button type="submit" name="fcc_clear_log" class="button button-secondary">Clear Log</button>
            </p>
        </form>
    </div>
    <?php
}

// Register settings
function fcc_register_settings() {
    register_setting('fcc_settings_group', 'fcc_auto_apply_new_users');
    register_setting('fcc_settings_group', 'fcc_auto_apply_new_spaces');

    add_settings_section('fcc_main_section', 'Preferences', null, 'fluentcommunity-settings');

    add_settings_field(
        'fcc_auto_apply_new_users',
        'Auto apply preferences for new users?',
        'fcc_checkbox_callback',
        'fluentcommunity-settings',
        'fcc_main_section',
        ['label_for' => 'fcc_auto_apply_new_users']
    );

    add_settings_field(
        'fcc_auto_apply_new_spaces',
        'Auto apply notifications to new spaces?',
        'fcc_checkbox_callback',
        'fluentcommunity-settings',
        'fcc_main_section',
        ['label_for' => 'fcc_auto_apply_new_spaces']
    );
}
add_action('admin_init', 'fcc_register_settings');

// Reusable checkbox field
function fcc_checkbox_callback($args) {
    $option = get_option($args['label_for'], false);
    ?>
    <input type="checkbox"
           id="<?php echo esc_attr($args['label_for']); ?>"
           name="<?php echo esc_attr($args['label_for']); ?>"
           value="1"
           <?php checked(1, $option, true); ?> />
    <?php
}