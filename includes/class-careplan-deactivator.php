<?php
defined('ABSPATH') || exit;

/**
 * Handles plugin deactivation.
 */
class CarePlan_Deactivator {
    public static function deactivate() {
        // Optional: clean up scheduled events
        wp_clear_scheduled_hook('careplan_cron_hook');

        // Optional: remove plugin-specific transient data
        global $wpdb;
        $tables = [
            $wpdb->prefix . 'careplan_participants',
            $wpdb->prefix . 'careplan_plans',
            $wpdb->prefix . 'careplan_goals',
            $wpdb->prefix . 'careplan_notes',
        ];

        // Uncomment below to delete tables on deactivate (use with caution)
        /*
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
        */
    }
}
