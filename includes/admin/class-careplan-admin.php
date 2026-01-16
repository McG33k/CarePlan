<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-participants-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-plans-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-goals-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-notes-repo.php';

class CarePlan_Admin {

    const CAPABILITY = 'manage_careplan';

    const PAGE_DASHBOARD  = 'careplan-dashboard';
    const PAGE_PLANS      = 'careplan-plans';
    const PAGE_GOALS      = 'careplan-goals';
    const PAGE_NOTES      = 'careplan-notes';

    private $participants_repo;
    private $plans_repo;
    private $goals_repo;
    private $notes_repo;

    public function __construct() {
        $this->participants_repo = new CarePlan_Participants_Repo();
        $this->plans_repo        = new CarePlan_Plans_Repo();
        $this->goals_repo        = new CarePlan_Goals_Repo();
        $this->notes_repo        = new CarePlan_Notes_Repo();

        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);

        add_action('admin_post_careplan_save_participant', [$this, 'save_participant']);
        add_action('admin_post_careplan_save_plan', [$this, 'save_plan']);
        add_action('admin_post_careplan_save_goal', [$this, 'save_goal']);
        add_action('admin_post_careplan_save_note', [$this, 'save_note']);

        add_action('admin_post_careplan_delete_participant', [$this, 'delete_participant']);
        add_action('admin_post_careplan_delete_plan', [$this, 'delete_plan']);
        add_action('admin_post_careplan_delete_goal', [$this, 'delete_goal']);
        add_action('admin_post_careplan_delete_note', [$this, 'delete_note']);

        add_action('admin_notices', [$this, 'admin_notices']);
    }

    public function enqueue_assets($hook) {
        if (strpos($hook, 'careplan') === false) {
            return;
        }

        wp_enqueue_style(
            'careplan-admin',
            CAREPLAN_URL . 'assets/css/admin.css',
            [],
            CAREPLAN_VERSION
        );
    }

    public function admin_notices() {
        if (!empty($_GET['updated'])) {
            echo '<div class="notice notice-success is-dismissible"><p>' .
                esc_html__('Saved successfully.', 'careplan') .
                '</p></div>';
        }

        if (!empty($_GET['deleted'])) {
            echo '<div class="notice notice-success is-dismissible"><p>' .
                esc_html__('Deleted successfully.', 'careplan') .
                '</p></div>';
        }
    }

    public function register_admin_menu() {
        add_menu_page(
            __('CarePlan', 'careplan'),
            __('CarePlan', 'careplan'),
            self::CAPABILITY,
            self::PAGE_DASHBOARD,
            [$this, 'participants_page'],
            'dashicons-clipboard',
            25
        );

        add_submenu_page(
            self::PAGE_DASHBOARD,
            __('Participants', 'careplan'),
            __('Participants', 'careplan'),
            self::CAPABILITY,
            self::PAGE_DASHBOARD,
            [$this, 'participants_page']
        );

        add_submenu_page(
            self::PAGE_DASHBOARD,
            __('Plans', 'careplan'),
            __('Plans', 'careplan'),
            self::CAPABILITY,
            self::PAGE_PLANS,
            [$this, 'plans_page']
        );

        add_submenu_page(
            self::PAGE_DASHBOARD,
            __('Goals', 'careplan'),
            __('Goals', 'careplan'),
            self::CAPABILITY,
            self::PAGE_GOALS,
            [$this, 'goals_page']
        );

        add_submenu_page(
            self::PAGE_DASHBOARD,
            __('Notes', 'careplan'),
            __('Notes', 'careplan'),
            self::CAPABILITY,
            self::PAGE_NOTES,
            [$this, 'notes_page']
        );
    }

    /* =======================
     * PAGE RENDERERS
     * ======================= */

    public function participants_page() {
        $participants = $this->participants_repo->get_all_participants();
        require CAREPLAN_PATH . 'includes/admin/templates/participants-list.php';
    }

    public function plans_page() {
        $plans = $this->plans_repo->get_all_plans();
        require CAREPLAN_PATH . 'includes/admin/templates/plans-list.php';
    }

    public function goals_page() {
        $goals = $this->goals_repo->get_all_goals();
        require CAREPLAN_PATH . 'includes/admin/templates/goals-list.php';
    }

    public function notes_page() {
        $notes = $this->notes_repo->get_all_notes();
        require CAREPLAN_PATH . 'includes/admin/templates/notes-list.php';
    }

    /* =======================
     * SECURITY HELPERS
     * ======================= */

    private function verify_post($nonce_action) {
        if (
            !current_user_can(self::CAPABILITY) ||
            empty($_POST['_wpnonce']) ||
            !wp_verify_nonce(wp_unslash($_POST['_wpnonce']), $nonce_action)
        ) {
            wp_die(__('Unauthorized action', 'careplan'));
        }
    }

    private function verify_get($nonce_action) {
        if (
            !current_user_can(self::CAPABILITY) ||
            empty($_GET['_wpnonce']) ||
            !wp_verify_nonce(wp_unslash($_GET['_wpnonce']), $nonce_action)
        ) {
            wp_die(__('Unauthorized action', 'careplan'));
        }
    }

    /* =======================
     * SAVE HANDLERS
     * ======================= */

    public function save_participant() {
        $this->verify_post('careplan_save_participant');

        $data = [
            'first_name'    => sanitize_text_field(wp_unslash($_POST['first_name'] ?? '')),
            'last_name'     => sanitize_text_field(wp_unslash($_POST['last_name'] ?? '')),
            'ndis_number'   => sanitize_text_field(wp_unslash($_POST['ndis_number'] ?? '')),
            'date_of_birth' => sanitize_text_field(wp_unslash($_POST['date_of_birth'] ?? '')),
        ];

        $id = absint($_POST['id'] ?? 0);

        $id ? $this->participants_repo->update_participant($id, $data)
            : $this->participants_repo->create_participant($data);

        wp_safe_redirect(admin_url('admin.php?page=' . self::PAGE_DASHBOARD . '&updated=1'));
        exit;
    }

    public function save_plan() {
        $this->verify_post('careplan_save_plan');

        $data = [
            'participant_id' => absint($_POST['participant_id'] ?? 0),
            'plan_start'     => sanitize_text_field(wp_unslash($_POST['plan_start'] ?? '')),
            'plan_end'       => sanitize_text_field(wp_unslash($_POST['plan_end'] ?? '')),
            'total_budget'   => floatval($_POST['total_budget'] ?? 0),
            'status'         => sanitize_text_field(wp_unslash($_POST['status'] ?? '')),
        ];

        $id = absint($_POST['id'] ?? 0);

        $id ? $this->plans_repo->update_plan($id, $data)
            : $this->plans_repo->create_plan($data);

        wp_safe_redirect(admin_url('admin.php?page=' . self::PAGE_PLANS . '&updated=1'));
        exit;
    }

    public function save_goal() {
        $this->verify_post('careplan_save_goal');

        $data = [
            'participant_id'   => absint($_POST['participant_id'] ?? 0),
            'goal_title'       => sanitize_text_field(wp_unslash($_POST['goal_title'] ?? '')),
            'goal_description' => sanitize_textarea_field(wp_unslash($_POST['goal_description'] ?? '')),
            'priority'         => absint($_POST['priority'] ?? 0),
            'status'           => sanitize_text_field(wp_unslash($_POST['status'] ?? '')),
        ];

        $id = absint($_POST['id'] ?? 0);

        $id ? $this->goals_repo->update_goal($id, $data)
            : $this->goals_repo->create_goal($data);

        wp_safe_redirect(admin_url('admin.php?page=' . self::PAGE_GOALS . '&updated=1'));
        exit;
    }

    public function save_note() {
        $this->verify_post('careplan_save_note');

        $data = [
            'participant_id' => absint($_POST['participant_id'] ?? 0),
            'note'           => sanitize_textarea_field(wp_unslash($_POST['note'] ?? '')),
            'created_by'     => get_current_user_id(),
        ];

        $id = absint($_POST['id'] ?? 0);

        $id ? $this->notes_repo->update_note($id, $data)
            : $this->notes_repo->create_note($data);

        wp_safe_redirect(admin_url('admin.php?page=' . self::PAGE_NOTES . '&updated=1'));
        exit;
    }

    /* =======================
     * DELETE HANDLERS
     * ======================= */

    public function delete_participant() {
        $this->verify_get('careplan_delete_participant');

        $id = absint($_GET['id'] ?? 0);
        if ($id) {
            $this->participants_repo->delete_participant($id);
        }

        wp_safe_redirect(admin_url('admin.php?page=' . self::PAGE_DASHBOARD . '&deleted=1'));
        exit;
    }

    public function delete_plan() {
        $this->verify_get('careplan_delete_plan');

        $id = absint($_GET['id'] ?? 0);
        if ($id) {
            $this->plans_repo->delete_plan($id);
        }

        wp_safe_redirect(admin_url('admin.php?page=' . self::PAGE_PLANS . '&deleted=1'));
        exit;
    }

    public function delete_goal() {
        $this->verify_get('careplan_delete_goal');

        $id = absint($_GET['id'] ?? 0);
        if ($id) {
            $this->goals_repo->delete_goal($id);
        }

        wp_safe_redirect(admin_url('admin.php?page=' . self::PAGE_GOALS . '&deleted=1'));
        exit;
    }

    public function delete_note() {
        $this->verify_get('careplan_delete_note');

        $id = absint($_GET['id'] ?? 0);
        if ($id) {
            $this->notes_repo->delete_note($id);
        }

        wp_safe_redirect(admin_url('admin.php?page=' . self::PAGE_NOTES . '&deleted=1'));
        exit;
    }
}
