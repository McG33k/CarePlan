<?php
defined( 'ABSPATH' ) || exit;

class CarePlan_Admin {

    private $participants_repo;
    private $plans_repo;
    private $goals_repo;
    private $notes_repo;

    public function __construct() {
        // Instantiate repositories
        $this->participants_repo = new CarePlan_Participants_Repo();
        $this->plans_repo        = new CarePlan_Plans_Repo();
        $this->goals_repo        = new CarePlan_Goals_Repo();
        $this->notes_repo        = new CarePlan_Notes_Repo();

        // Admin hooks
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    /**
     * Register admin menu
     */
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

    /**
     * Render dashboard
     */
    public function render_dashboard() {
        require CAREPLAN_PATH . 'templates/admin-dashboard.php';
    }

    /**
     * Enqueue admin assets
     */
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

        wp_enqueue_script(
            'careplan-admin',
            CAREPLAN_URL . 'assets/js/admin.js',
            ['jquery'],
            CAREPLAN_VERSION,
            true
        );
    }
}
