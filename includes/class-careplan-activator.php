<?php
defined('ABSPATH') || exit;

/**
 * Handles plugin activation tasks for CarePlan.
 */
class CarePlan_Activator {

    /**
     * Database schema version.
     */
    const DB_VERSION = '1.0';

    /**
     * Run on plugin activation.
     */
    public static function activate() {
        self::create_tables();
        self::set_db_version();
        self::add_administrator_capabilities();
        self::add_ndis_roles();
    }

    /**
     * Create database tables using dbDelta.
     */
    private static function create_tables() {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();

        $participants_table = $wpdb->prefix . 'careplan_participants';
        $plans_table        = $wpdb->prefix . 'careplan_plans';
        $goals_table        = $wpdb->prefix . 'careplan_goals';
        $notes_table        = $wpdb->prefix . 'careplan_notes';

        // Participants Table
        $sql_participants = "
        CREATE TABLE {$participants_table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            ndis_number VARCHAR(50) NOT NULL,
            date_of_birth DATE NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY ndis_number (ndis_number)
        ) {$charset_collate};
        ";

        // Plans Table
        $sql_plans = "
        CREATE TABLE {$plans_table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            participant_id BIGINT(20) UNSIGNED NOT NULL,
            plan_start DATE NOT NULL,
            plan_end DATE NOT NULL,
            total_budget DECIMAL(12,2) NOT NULL DEFAULT 0,
            status ENUM('active','completed','paused') NOT NULL DEFAULT 'active',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY participant_id (participant_id),
            KEY status (status),
            CONSTRAINT fk_plans_participant FOREIGN KEY (participant_id) REFERENCES {$participants_table}(id) ON DELETE CASCADE
        ) {$charset_collate};
        ";

        // Goals Table
        $sql_goals = "
        CREATE TABLE {$goals_table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            plan_id BIGINT(20) UNSIGNED NOT NULL,
            goal_title VARCHAR(255) NOT NULL,
            goal_description TEXT NULL,
            priority INT(11) NOT NULL DEFAULT 0,
            status ENUM('open','in_progress','completed') NOT NULL DEFAULT 'open',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY plan_id (plan_id),
            KEY status (status),
            CONSTRAINT fk_goals_plan FOREIGN KEY (plan_id) REFERENCES {$plans_table}(id) ON DELETE CASCADE
        ) {$charset_collate};
        ";

        // Notes Table
        $sql_notes = "
        CREATE TABLE {$notes_table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            goal_id BIGINT(20) UNSIGNED NOT NULL,
            note TEXT NOT NULL,
            created_by BIGINT(20) UNSIGNED NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY goal_id (goal_id),
            KEY created_by (created_by),
            CONSTRAINT fk_notes_goal FOREIGN KEY (goal_id) REFERENCES {$goals_table}(id) ON DELETE CASCADE
        ) {$charset_collate};
        ";

        // Run dbDelta to create or update tables
        dbDelta($sql_participants);
        dbDelta($sql_plans);
        dbDelta($sql_goals);
        dbDelta($sql_notes);
    }

    /**
     * Store DB version for future migrations.
     */
    private static function set_db_version() {
        update_option('careplan_db_version', self::DB_VERSION);
    }

    /**
     * Add plugin-specific capabilities to administrator role.
     */
    private static function add_administrator_capabilities() {
        $role = get_role('administrator');
        if (!$role) {
            return;
        }

        $caps = [
            'manage_careplan',
            'edit_careplan_participants',
            'edit_careplan_plans',
            'edit_careplan_goals',
            'edit_careplan_notes',
            'delete_careplan_data',
        ];

        foreach ($caps as $cap) {
            if (!$role->has_cap($cap)) {
                $role->add_cap($cap);
            }
        }
    }

    /**
     * Add NDIS-specific roles with limited capabilities.
     */
    private static function add_ndis_roles() {
        // Define the capabilities for NDIS Coordinators
        $ndis_caps = [
            'edit_careplan_participants',
            'edit_careplan_plans',
            'edit_careplan_goals',
            'edit_careplan_notes',
        ];

        // Add role if it doesn't exist
        if (!get_role('ndis_coordinator')) {
            add_role('ndis_coordinator', 'NDIS Coordinator', array_merge(['read' => true], array_fill_keys($ndis_caps, true)));
        } else {
            // Update capabilities if role exists
            $role = get_role('ndis_coordinator');
            foreach ($ndis_caps as $cap) {
                if (!$role->has_cap($cap)) {
                    $role->add_cap($cap);
                }
            }
        }
    }
}
