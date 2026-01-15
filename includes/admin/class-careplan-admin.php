<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-participants-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-plans-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-goals-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-notes-repo.php';

class CarePlan_Admin {

    private $participants_repo;
    private $plans_repo;
    private $goals_repo;
    private $notes_repo;

    public function __construct() {
        $this->participants_repo = new CarePlan_Participants_Repo();
        $this->plans_repo = new CarePlan_Plans_Repo();
        $this->goals_repo = new CarePlan_Goals_Repo();
        $this->notes_repo = new CarePlan_Notes_Repo();
    }

    public function init() {
        add_action('admin_menu', [$this, 'register_admin_menu']);

        // Save handlers
        add_action('admin_post_careplan_save_participant', [$this, 'save_participant']);
        add_action('admin_post_careplan_save_plan', [$this, 'save_plan']);
        add_action('admin_post_careplan_save_goal', [$this, 'save_goal']);
        add_action('admin_post_careplan_save_note', [$this, 'save_note']);

        // Delete handlers
        add_action('admin_post_careplan_delete_plan', [$this, 'delete_plan']);
        add_action('admin_post_careplan_delete_goal', [$this, 'delete_goal']);
        add_action('admin_post_careplan_delete_note', [$this, 'delete_note']);

        // Admin notices
        add_action('admin_notices', [$this, 'admin_notices']);
    }

    /**
     * Admin notices for actions
     */
    public function admin_notices() {
        if (isset($_GET['updated']) && $_GET['updated'] == 1) {
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Saved successfully.', 'careplan') . '</p></div>';
        }
        if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Deleted successfully.', 'careplan') . '</p></div>';
        }
    }

    /**
     * Register Admin Menu
     */
    public function register_admin_menu() {
        add_menu_page(
            __('CarePlan', 'careplan'),
            __('CarePlan', 'careplan'),
            'manage_options',
            'careplan-dashboard',
            [$this, 'participants_page'],
            'dashicons-clipboard',
            25
        );

        add_submenu_page(
            'careplan-dashboard',
            __('Participants', 'careplan'),
            __('Participants', 'careplan'),
            'manage_options',
            'careplan-dashboard',
            [$this, 'participants_page']
        );

        add_submenu_page(
            'careplan-dashboard',
            __('Plans', 'careplan'),
            __('Plans', 'careplan'),
            'manage_options',
            'careplan-plans',
            [$this, 'plans_page']
        );

        add_submenu_page(
            'careplan-dashboard',
            __('Goals', 'careplan'),
            __('Goals', 'careplan'),
            'manage_options',
            'careplan-goals',
            [$this, 'goals_page']
        );

        add_submenu_page(
            'careplan-dashboard',
            __('Notes', 'careplan'),
            __('Notes', 'careplan'),
            'manage_options',
            'careplan-notes',
            [$this, 'notes_page']
        );
    }

    /**
     * Participants Page
     */
    public function participants_page() {
        $participants = $this->participants_repo->get_all_participants();
        include CAREPLAN_PATH . 'includes/admin/templates/participants-list.php';
    }

    /**
     * Plans Page
     */
    public function plans_page() {
        $plans = $this->plans_repo->get_all_plans();
        include CAREPLAN_PATH . 'includes/admin/templates/plans-list.php';
    }

    /**
     * Goals Page
     */
    public function goals_page() {
        $goals = $this->goals_repo->get_all_goals();
        include CAREPLAN_PATH . 'includes/admin/templates/goals-list.php';
    }

    /**
     * Notes Page
     */
    public function notes_page() {
        $notes = $this->notes_repo->get_all_notes();
        include CAREPLAN_PATH . 'includes/admin/templates/notes-list.php';
    }

    /**
     * Save Participant
     */
    public function save_participant() {
        if (!current_user_can('manage_options') || !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'careplan_save_participant')) {
            wp_die(__('Unauthorized action', 'careplan'));
        }

        $data = [
            'first_name' => sanitize_text_field($_POST['first_name'] ?? ''),
            'last_name' => sanitize_text_field($_POST['last_name'] ?? ''),
            'ndis_number' => sanitize_text_field($_POST['ndis_number'] ?? ''),
            'date_of_birth' => sanitize_text_field($_POST['date_of_birth'] ?? ''),
        ];

        $id = intval($_POST['id'] ?? 0);

        if ($id) {
            $this->participants_repo->update_participant($id, $data);
        } else {
            $this->participants_repo->create_participant($data);
        }

        wp_redirect(admin_url('admin.php?page=careplan-dashboard&updated=1'));
        exit;
    }

    /**
     * Save Plan
     */
    public function save_plan() {
        if (!current_user_can('manage_options') || !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'careplan_save_plan')) {
            wp_die(__('Unauthorized action', 'careplan'));
        }

        $data = [
            'participant_id' => intval($_POST['participant_id']),
            'plan_start' => sanitize_text_field($_POST['plan_start']),
            'plan_end' => sanitize_text_field($_POST['plan_end']),
            'total_budget' => floatval($_POST['total_budget']),
            'status' => sanitize_text_field($_POST['status']),
        ];

        $id = intval($_POST['id'] ?? 0);

        if ($id) {
            $this->plans_repo->update_plan($id, $data);
        } else {
            $this->plans_repo->create_plan($data);
        }

        wp_redirect(admin_url('admin.php?page=careplan-plans&updated=1'));
        exit;
    }

    /**
     * Save Goal
     */
    public function save_goal() {
        if (!current_user_can('manage_options') || !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'careplan_save_goal')) {
            wp_die(__('Unauthorized action', 'careplan'));
        }

        $data = [
            'participant_id' => intval($_POST['participant_id']),
            'goal_title' => sanitize_text_field($_POST['goal_title']),
            'goal_description' => sanitize_textarea_field($_POST['goal_description']),
            'priority' => intval($_POST['priority']),
            'status' => sanitize_text_field($_POST['status']),
        ];

        $id = intval($_POST['id'] ?? 0);

        if ($id) {
            $this->goals_repo->update_goal($id, $data);
        } else {
            $this->goals_repo->create_goal($data);
        }

        wp_redirect(admin_url('admin.php?page=careplan-goals&updated=1'));
        exit;
    }

    /**
     * Save Note
     */
    public function save_note() {
        if (!current_user_can('manage_options') || !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'careplan_save_note')) {
            wp_die(__('Unauthorized action', 'careplan'));
        }

        $data = [
            'participant_id' => intval($_POST['participant_id']),
            'note' => sanitize_textarea_field($_POST['note']),
            'created_by' => get_current_user_id(),
        ];

        $id = intval($_POST['id'] ?? 0);

        if ($id) {
            $this->notes_repo->update_note($id, $data);
        } else {
            $this->notes_repo->create_note($data);
        }

        wp_redirect(admin_url('admin.php?page=careplan-notes&updated=1'));
        exit;
    }

    /**
     * Delete Plan
     */
    public function delete_plan() {
        if (!current_user_can('manage_options') || !isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'careplan_delete_plan')) {
            wp_die(__('Unauthorized action', 'careplan'));
        }

        $id = intval($_GET['id'] ?? 0);

        if ($id) {
            $this->plans_repo->delete_plan($id);
        }

        wp_redirect(admin_url('admin.php?page=careplan-participant-detail&participant_id=' . intval($_GET['participant_id'] ?? 0) . '&tab=plans&deleted=1'));
        exit;
    }

    /**
     * Delete Goal
     */
    public function delete_goal() {
        if (!current_user_can('manage_options') || !isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'careplan_delete_goal')) {
            wp_die(__('Unauthorized action', 'careplan'));
        }

        $id = intval($_GET['id'] ?? 0);

        if ($id) {
            $this->goals_repo->delete_goal($id);
        }

        wp_redirect(admin_url('admin.php?page=careplan-participant-detail&participant_id=' . intval($_GET['participant_id'] ?? 0) . '&tab=goals&deleted=1'));
        exit;
    }

    /**
     * Delete Note
     */
    public function delete_note() {
        if (!current_user_can('manage_options') || !isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'careplan_delete_note')) {
            wp_die(__('Unauthorized action', 'careplan'));
        }

        $id = intval($_GET['id'] ?? 0);

        if ($id) {
            $this->notes_repo->delete_note($id);
        }

        wp_redirect(admin_url('admin.php?page=careplan-participant-detail&participant_id=' . intval($_GET['participant_id'] ?? 0) . '&tab=notes&deleted=1'));
        exit;
    }
}
