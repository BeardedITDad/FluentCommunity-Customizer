<?php
defined('ABSPATH') || exit;

/**
 * FluentCommunity Customizer: Auto Notification Logic
 * - Applies default notification settings to new users
 * - Syncs notification prefs for new spaces
 * - Adds manual admin action to apply settings to all users
 */

/*--------------------------------------------
 | 1. Apply default prefs for new users
 *-------------------------------------------*/
add_action('user_register', 'fcom_set_default_notifications_for_new_users');

function fcom_set_default_notifications_for_new_users($user_id) {
    if (get_option('fcom_enable_user_defaults', '1') !== '1') {
        fcc_log("Skipped setting user defaults for user ID $user_id (disabled in settings).");
        return;
    }

    global $wpdb;
    $notification_table = $wpdb->prefix . 'fcom_notification_users';
    $now = current_time('mysql');

    $prefs = ['digest_mail', 'mention_mail', 'reply_my_com_mail'];

    foreach ($prefs as $type) {
        $wpdb->insert($notification_table, [
            'object_type'        => 'notification_pref',
            'notification_type'  => $type,
            'object_id'          => null,
            'user_id'            => $user_id,
            'is_read'            => 1,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);
    }

    fcc_log("✅ Default notifications applied to new user ID $user_id.");
}


/*--------------------------------------------
 | 2. Auto-apply per-space prefs for new spaces
 *-------------------------------------------*/
add_action('admin_init', 'fcom_check_for_new_spaces');

function fcom_check_for_new_spaces() {
    if (get_option('fcom_enable_space_sync', '1') !== '1') {
        return;
    }

    global $wpdb;
    $space_table        = $wpdb->prefix . 'fcom_spaces';
    $notification_table = $wpdb->prefix . 'fcom_notification_users';
    $now                = current_time('mysql');
    $space_types        = ['np_by_member_mail', 'np_by_admin_mail'];

    $current_spaces = $wpdb->get_col("SELECT id FROM $space_table");
    $stored_spaces  = get_option('fcom_known_space_ids', []);
    $new_spaces     = array_diff($current_spaces, $stored_spaces);

    if (empty($new_spaces)) {
        return;
    }

    $users = get_users(['fields' => ['ID']]);

    foreach ($new_spaces as $space_id) {
        foreach ($users as $user) {
            foreach ($space_types as $type) {
                $exists = $wpdb->get_var($wpdb->prepare(
                    "SELECT id FROM $notification_table
                     WHERE object_type = 'notification_pref'
                     AND notification_type = %s
                     AND object_id = %d
                     AND user_id = %d",
                    $type, $space_id, $user->ID
                ));

                if (!$exists) {
                    $wpdb->insert($notification_table, [
                        'object_type'        => 'notification_pref',
                        'notification_type'  => $type,
                        'object_id'          => $space_id,
                        'user_id'            => $user->ID,
                        'is_read'            => 1,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ]);
                }
            }
        }

        fcc_log("✅ Applied new space ID $space_id notification defaults to all users.");
    }

    update_option('fcom_known_space_ids', $current_spaces);
}


/*-----------------------------------------------------------
 | 3. Manual action: Apply default prefs to all existing users
 *----------------------------------------------------------*/
add_action('admin_post_fcom_apply_notifications_now', 'fcom_apply_notifications_to_all_users');

function fcom_apply_notifications_to_all_users() {
    if (!current_user_can('manage_options') || !check_admin_referer('fcom_apply_notifications')) {
        wp_die('Not allowed');
    }

    global $wpdb;
    $notification_table = $wpdb->prefix . 'fcom_notification_users';
    $now = current_time('mysql');
    $users = get_users(['fields' => ['ID']]);
    $prefs = ['digest_mail', 'mention_mail', 'reply_my_com_mail'];

    foreach ($users as $user) {
        foreach ($prefs as $type) {
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM $notification_table
                 WHERE object_type = 'notification_pref'
                 AND notification_type = %s
                 AND object_id IS NULL
                 AND user_id = %d",
                $type, $user->ID
            ));

            if (!$exists) {
                $wpdb->insert($notification_table, [
                    'object_type'        => 'notification_pref',
                    'notification_type'  => $type,
                    'object_id'          => null,
                    'user_id'            => $user->ID,
                    'is_read'            => 1,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]);
            }
        }
    }

    fcc_log("✅ Manually applied default notifications to all users.");
    wp_redirect(admin_url('options-general.php?page=fcom-settings&notifications=success'));
    exit;
}