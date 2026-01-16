<?php
defined('ABSPATH') || exit;

/**
 * Core plugin class.
 *
 * Handles initialization of the CarePlan plugin.
 */
class CarePlan {

    /**
     * Constructor.
     * Initialize core plugin features.
     */
    public function __construct() {
        // Initialize core components here if needed
        // e.g., custom post types, shortcodes, custom REST endpoints
    }

    /**
     * Run the plugin.
     * This method is called from careplan.php on 'plugins_loaded'
     */
    public function run() {
        // Initialize plugin hooks here
        // Currently, all admin functionality is in CarePlan_Admin
        // Add core plugin hooks in the future if needed

        // Example: load plugin textdomain for translations
        $this->load_textdomain();
    }

    /**
     * Load plugin translations.
     */
    private function load_textdomain() {
        load_plugin_textdomain(
            'careplan',
            false,
            dirname(plugin_basename(CAREPLAN_FILE)) . '/languages/'
        );
    }

    /**
     * Optional: You can add other core methods here
     * - Custom post types
     * - Shortcodes
     * - REST API endpoints
     * - Cron tasks
     */
}
