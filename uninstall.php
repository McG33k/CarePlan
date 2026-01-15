<?php
defined('WP_UNINSTALL_PLUGIN') || exit;

global $wpdb;

$tables = [
    "{$wpdb->prefix}careplan_participants",
    "{$wpdb->prefix}careplan_plans",
    "{$wpdb->prefix}careplan_goals",
    "{$wpdb->prefix}careplan_notes",
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS $table");
}
