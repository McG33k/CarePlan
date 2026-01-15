<?php
class CarePlan_Admin {

    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
    }

    public function menu() {
        add_menu_page(
            'CarePlan',
            'CarePlan',
            'manage_options',
            'careplan',
            [$this, 'dashboard'],
            'dashicons-clipboard'
        );
    }

    public function dashboard() {
        include plugin_dir_path(__FILE__) . '../templates/dashboard.php';
    }
}
