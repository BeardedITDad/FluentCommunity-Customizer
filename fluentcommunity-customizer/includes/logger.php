<?php

// Path to the plugin log file
function fcc_get_log_file_path() {
    return plugin_dir_path(__DIR__) . '../fluentcommunity.log';
}

// Log a message with timestamp
function fcc_log($message) {
    $log_file = fcc_get_log_file_path();
    $date = date('Y-m-d H:i:s');
    $entry = "[$date] $message" . PHP_EOL;

    // Ensure file is writable
    if (is_writable(dirname($log_file)) || !file_exists($log_file)) {
        file_put_contents($log_file, $entry, FILE_APPEND);
    } else {
        error_log("FluentCommunity Customizer: Log file is not writable at $log_file");
    }
}

// Get the full contents of the log file
function fcc_get_log_contents() {
    $log_file = fcc_get_log_file_path();
    return file_exists($log_file) ? file_get_contents($log_file) : '';
}

// Clear the log file
function fcc_clear_log() {
    $log_file = fcc_get_log_file_path();
    if (file_exists($log_file)) {
        file_put_contents($log_file, '');
        return true;
    }
    return false;
}