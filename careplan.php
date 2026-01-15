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
 */

defined('ABSPATH') || exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-careplan-cpt.php';
new CarePlan_CPT();


require_once plugin_dir_path(__FILE__) . 'includes/class-careplan-admin.php';
new CarePlan_Admin();

