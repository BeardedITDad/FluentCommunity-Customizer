// When a new space is created, update all users with per-space notification prefs
add_action('admin_init', 'fcom_check_for_new_spaces');

function fcom_check_for_new_spaces() {
    global $wpdb;

    $table = $wpdb->prefix . 'fcom_spaces';
    $notification_table = $wpdb->prefix . 'fcom_notification_users';
    $now = current_time('mysql');

    $space_types = [
        'np_by_member_mail',
        'np_by_admin_mail'
    ];

    // Get all current space IDs
    $current_spaces = $wpdb->get_col("SELECT id FROM $table");

    // Get stored space IDs from wp_options
    $stored_spaces = get_option('fcom_known_space_ids', []);

    // Find new spaces
    $new_spaces = array_diff($current_spaces, $stored_spaces);

    if (!empty($new_spaces)) {
        $users = get_users(['fields' => ['ID']]);

        foreach ($new_spaces as $space_id) {
            foreach ($users as $user) {
                foreach ($space_types as $type) {
                    // Avoid duplicates by checking first
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
                            'object_type' => 'notification_pref',
                            'notification_type' => $type,
                            'object_id' => $space_id,
                            'user_id' => $user->ID,
                            'is_read' => 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }
        }

        // Update the known space list
        update_option('fcom_known_space_ids', $current_spaces);

        // Optional: Log for debugging
        error_log("FluentCommunity: Added notification prefs for new spaces: " . implode(', ', $new_spaces));
    }
}
