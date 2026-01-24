<?php
/**
 * Plugin Name: CarePlan
 * Plugin URI: https://careplanwp.com
 * Description: Manage participant care plans, goals, and support coordination. Built for disability and care service providers.
 * Version: 0.1.0
 * Author: Cornelius Nyamutenha
 * Author URI: https://github.com/McG33k
 * Text Domain: careplan
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || exit;

/**
 * ---------------------------------------------------------
 * Plugin Constants
 * ---------------------------------------------------------
 */
define( 'CAREPLAN_VERSION', '0.1.0' );
define( 'CAREPLAN_FILE', __FILE__ );
define( 'CAREPLAN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CAREPLAN_URL', plugin_dir_url( __FILE__ ) );

/**
 * ---------------------------------------------------------
 * Core Includes
 * ---------------------------------------------------------
 */
// Activation / Deactivation
require_once CAREPLAN_PATH . 'includes/class-careplan-activator.php';
require_once CAREPLAN_PATH . 'includes/class-careplan-deactivator.php';

// Core Plugin Class
require_once CAREPLAN_PATH . 'includes/class-careplan.php';

// Internationalization
require_once CAREPLAN_PATH . 'includes/class-careplan-i18n.php';

/**
 * ---------------------------------------------------------
 * Repositories (must load before admin)
 * ---------------------------------------------------------
 */
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-participants-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-plans-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-goals-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-notes-repo.php';

/**
 * ---------------------------------------------------------
 * Admin functionality
 * ---------------------------------------------------------
 */
require_once CAREPLAN_PATH . 'includes/admin/class-careplan-admin.php';

/**
 * ---------------------------------------------------------
 * Frontend functionality
 * ---------------------------------------------------------
 */
if ( ! is_admin() ) {
    require_once CAREPLAN_PATH . 'includes/class-careplan-frontend.php';
}

/**
 * ---------------------------------------------------------
 * Activation / Deactivation Hooks
 * ---------------------------------------------------------
 */
register_activation_hook( CAREPLAN_FILE, [ 'CarePlan_Activator', 'activate' ] );
register_deactivation_hook( CAREPLAN_FILE, [ 'CarePlan_Deactivator', 'deactivate' ] );

/**
 * ---------------------------------------------------------
 * Plugin Bootstrap (Core, Admin, Frontend)
 * ---------------------------------------------------------
 */
add_action( 'plugins_loaded', function() {
    // Core plugin
    if ( class_exists( 'CarePlan' ) ) {
        $plugin = new CarePlan();
        $plugin->run();
    }

    // Admin
    if ( is_admin() && class_exists( 'CarePlan_Admin' ) ) {
        new CarePlan_Admin();
    }

    // Frontend
    if ( ! is_admin() && class_exists( 'CarePlan_Frontend' ) ) {
        new CarePlan_Frontend();
    }

    /* Load Shortcodes */
    require_once CAREPLAN_PATH . 'includes/shortcodes/class-careplan-shortcodes.php';
    new CarePlan_Shortcodes();



});
