<?php
defined( 'ABSPATH' ) || exit;

class CarePlan_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function register_menu() {

        add_menu_page(
            __( 'CarePlan', 'careplan' ),
            __( 'CarePlan', 'careplan' ),
            'manage_options',
            'careplan',
            [ $this, 'render_dashboard' ],
            'dashicons-clipboard',
            26
        );

        add_submenu_page(
            'careplan',
            __( 'Participants', 'careplan' ),
            __( 'Participants', 'careplan' ),
            'manage_options',
            'edit.php?post_type=careplan_participant'
        );
    }

    public function render_dashboard() {
        require CAREPLAN_PATH . 'templates/admin-dashboard.php';
    }

    public function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'careplan' ) === false ) {
            return;
        }

        wp_enqueue_style(
            'careplan-admin',
            CAREPLAN_URL . 'assets/css/admin.css',
            [],
            CAREPLAN_VERSION
        );
    }
}
