<?php
defined('ABSPATH') || exit;

class CarePlan_Activator {

    public static function activate() {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();

        $tables = [];

        $tables[] = "CREATE TABLE {$wpdb->prefix}careplan_participants (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NULL,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            ndis_number VARCHAR(50),
            date_of_birth DATE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}careplan_plans (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            participant_id BIGINT UNSIGNED NOT NULL,
            plan_start DATE,
            plan_end DATE,
            total_budget DECIMAL(10,2),
            status VARCHAR(20) DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY participant_id (participant_id)
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}careplan_goals (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            participant_id BIGINT UNSIGNED NOT NULL,
            goal_title VARCHAR(255),
            goal_description TEXT,
            priority INT DEFAULT 0,
            status VARCHAR(20) DEFAULT 'open',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY participant_id (participant_id)
        ) $charset_collate;";

        $tables[] = "CREATE TABLE {$wpdb->prefix}careplan_notes (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            participant_id BIGINT UNSIGNED NOT NULL,
            note TEXT,
            created_by BIGINT UNSIGNED,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY participant_id (participant_id)
        ) $charset_collate;";

        foreach ($tables as $sql) {
            dbDelta($sql);
        }
    }
}
