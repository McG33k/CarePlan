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

// Admin functionality
require_once CAREPLAN_PATH . 'includes/admin/class-careplan-admin.php';

/**
 * ---------------------------------------------------------
 * Activation Hooks
 * ---------------------------------------------------------
 */
register_activation_hook( CAREPLAN_FILE, [ 'CarePlan_Activator', 'activate' ] );
register_deactivation_hook( CAREPLAN_FILE, [ 'CarePlan_Deactivator', 'deactivate' ] );

/**
 * ---------------------------------------------------------
 * Plugin Bootstrap
 * ---------------------------------------------------------
 */
function run_careplan() {
    $plugin = new CarePlan();
    $plugin->run();
}

add_action( 'plugins_loaded', 'run_careplan' );
