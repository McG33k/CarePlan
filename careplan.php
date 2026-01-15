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

// Plugin constants.
define( 'CAREPLAN_VERSION', '0.1.0' );
define( 'CAREPLAN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CAREPLAN_URL', plugin_dir_url( __FILE__ ) );

// Core includes.
require_once CAREPLAN_PATH . 'includes/class-careplan.php';
require_once CAREPLAN_PATH . 'includes/class-careplan-i18n.php';
require_once CAREPLAN_PATH . 'includes/class-careplan-cpt.php';
require_once CAREPLAN_PATH . 'includes/class-careplan-admin.php';

// Activation / Deactivation.
register_activation_hook( __FILE__, [ 'CarePlan', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'CarePlan', 'deactivate' ] );

// Initialize plugin.
add_action( 'plugins_loaded', [ 'CarePlan', 'init' ] );