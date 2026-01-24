<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-participants-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-plans-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-goals-repo.php';
require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-notes-repo.php';

class CarePlan_Shortcodes {

    private $participants_repo;
    private $plans_repo;
    private $goals_repo;
    private $notes_repo;

    public function __construct() {
        $this->participants_repo = new CarePlan_Participants_Repo();
        $this->plans_repo        = new CarePlan_Plans_Repo();
        $this->goals_repo        = new CarePlan_Goals_Repo();
        $this->notes_repo        = new CarePlan_Notes_Repo();

        // Register shortcodes
        add_shortcode('careplan_participants', [$this, 'render_participants']);
        add_shortcode('careplan_plans', [$this, 'render_plans']);
        add_shortcode('careplan_goals', [$this, 'render_goals']);
        add_shortcode('careplan_notes', [$this, 'render_notes']);

        // Enqueue DataTables scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
        wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css', [], '1.13.6');
        wp_enqueue_style('datatables-buttons-css', 'https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css', [], '2.4.1');

        wp_enqueue_script('jquery');
        wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', ['jquery'], '1.13.6', true);
        wp_enqueue_script('datatables-buttons-js', 'https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js', ['datatables-js'], '2.4.1', true);
        wp_enqueue_script('datatables-buttons-html5', 'https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js', ['datatables-buttons-js'], '2.4.1', true);
        wp_enqueue_script('datatables-buttons-print', 'https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js', ['datatables-buttons-js'], '2.4.1', true);
        wp_enqueue_script('jszip', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js', [], '3.10.1', true);
        wp_enqueue_script('pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js', [], '0.2.7', true);
        wp_enqueue_script('pdfmake-vfs', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js', ['pdfmake'], '0.2.7', true);

        // Initialize DataTables
        add_action('wp_footer', function() {
            ?>
            <script>
                jQuery(document).ready(function($){
                    $('.careplan-table').DataTable({
                        responsive: true,
                        dom: 'Bfrtip',
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                        pageLength: 10
                    });
                });
            </script>
            <style>
                .careplan-table-container { overflow-x:auto; margin-bottom:2em; }
                .careplan-table th, .careplan-table td { padding:8px 12px; text-align:left; }
            </style>
            <?php
        });
    }

    /* ===============================
       PARTICIPANTS
    =============================== */
    public function render_participants() {
        $participants = $this->participants_repo->get_all_participants();

        ob_start();
        ?>
        <div class="careplan-table-container">
            <table class="careplan-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>NDIS Number</th>
                        <th>Date of Birth</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $p): ?>
                        <tr>
                            <td><?php echo esc_html($p->id); ?></td>
                            <td><?php echo esc_html($p->first_name); ?></td>
                            <td><?php echo esc_html($p->last_name); ?></td>
                            <td><?php echo esc_html($p->ndis_number); ?></td>
                            <td><?php echo esc_html($p->date_of_birth); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

    /* ===============================
       PLANS
    =============================== */
    public function render_plans() {
        $plans = $this->plans_repo->get_all_plans();

        // Attach participant names
        foreach ($plans as $plan) {
            $participant = $this->participants_repo->get_participant($plan->participant_id);
            $plan->participant_name = $participant ? $participant->first_name . ' ' . $participant->last_name : '';
        }

        ob_start();
        ?>
        <div class="careplan-table-container">
            <table class="careplan-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Participant</th>
                        <th>Plan Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Budget</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plans as $plan): ?>
                        <tr>
                            <td><?php echo esc_html($plan->id); ?></td>
                            <td><?php echo esc_html($plan->participant_name); ?></td>
                            <td><?php echo esc_html($plan->plan_title); ?></td>
                            <td><?php echo esc_html($plan->plan_start); ?></td>
                            <td><?php echo esc_html($plan->plan_end); ?></td>
                            <td><?php echo esc_html($plan->total_budget); ?></td>
                            <td><?php echo esc_html($plan->status); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

    /* ===============================
       GOALS
    =============================== */
    public function render_goals() {
        $goals = $this->goals_repo->get_all_goals();
        foreach ($goals as $goal) {
            $plan = $this->plans_repo->get_plan($goal->plan_id);
            $participant = $plan ? $this->participants_repo->get_participant($plan->participant_id) : null;
            $goal->plan_title = $plan ? $plan->plan_title : '';
            $goal->participant_name = $participant ? $participant->first_name . ' ' . $participant->last_name : '';
        }

        ob_start();
        ?>
        <div class="careplan-table-container">
            <table class="careplan-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Participant</th>
                        <th>Plan</th>
                        <th>Goal Title</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($goals as $goal): ?>
                        <tr>
                            <td><?php echo esc_html($goal->id); ?></td>
                            <td><?php echo esc_html($goal->participant_name); ?></td>
                            <td><?php echo esc_html($goal->plan_title); ?></td>
                            <td><?php echo esc_html($goal->goal_title); ?></td>
                            <td><?php echo esc_html($goal->goal_description); ?></td>
                            <td><?php echo esc_html($goal->priority); ?></td>
                            <td><?php echo esc_html($goal->status); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

    /* ===============================
       NOTES
    =============================== */
    public function render_notes() {
        $notes = $this->notes_repo->get_all_notes();
        foreach ($notes as $note) {
            $goal = $this->goals_repo->get_goal($note->goal_id);
            $plan = $goal ? $this->plans_repo->get_plan($goal->plan_id) : null;
            $participant = $plan ? $this->participants_repo->get_participant($plan->participant_id) : null;

            $note->goal_title = $goal ? $goal->goal_title : '';
            $note->participant_name = $participant ? $participant->first_name . ' ' . $participant->last_name : '';
        }

        ob_start();
        ?>
        <div class="careplan-table-container">
            <table class="careplan-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Participant</th>
                        <th>Goal</th>
                        <th>Note</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $note): ?>
                        <tr>
                            <td><?php echo esc_html($note->id); ?></td>
                            <td><?php echo esc_html($note->participant_name); ?></td>
                            <td><?php echo esc_html($note->goal_title); ?></td>
                            <td><?php echo esc_html($note->note); ?></td>
                            <td><?php echo esc_html($note->created_by); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

}
