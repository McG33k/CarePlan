<?php
defined( 'ABSPATH' ) || exit;

class CarePlan {

    public static function init() {
        // Load translations.
        CarePlan_i18n::load_textdomain();

        // Register CPTs.
        new CarePlan_CPT();

        // Admin functionality.
        if ( is_admin() ) {
            new CarePlan_Admin();
        }
    }

    public static function activate() {
        // Ensure CPTs exist before flushing.
        new CarePlan_CPT();
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}
