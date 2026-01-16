<?php
/**
 * CarePlan Plugin Uninstall
 *
 * Removes all plugin data when the plugin is deleted.
 *
 * @package CarePlan
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

/**
 * ------------------------------------
 * Define tables to delete
 * ------------------------------------
 */
$tables = [
    $wpdb->prefix . 'careplan_participants',
    $wpdb->prefix . 'careplan_plans',
    $wpdb->prefix . 'careplan_goals',
    $wpdb->prefix . 'careplan_notes',
];

/**
 * ------------------------------------
 * Drop custom tables
 * ------------------------------------
 */
foreach ( $tables as $table ) {
    $wpdb->query( "DROP TABLE IF EXISTS {$table}" );
}

/**
 * ------------------------------------
 * Delete plugin options
 * ------------------------------------
 */
delete_option( 'careplan_db_version' );
delete_option( 'careplan_settings' );

/**
 * ------------------------------------
 * Delete user meta related to CarePlan
 * ------------------------------------
 */
$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE %s",
        $wpdb->esc_like( 'careplan_' ) . '%'
    )
);

/**
 * ------------------------------------
 * Clear transients used by CarePlan
 * ------------------------------------
 */
$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE %s OR option_name LIKE %s",
        $wpdb->esc_like( '_transient_careplan_' ) . '%',
        $wpdb->esc_like( '_transient_timeout_careplan_' ) . '%'
    )
);
