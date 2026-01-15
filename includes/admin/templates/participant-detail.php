<?php
defined('ABSPATH') || exit;

/** @var $participant array */
/** @var $plans array */
/** @var $goals array */
/** @var $notes array */

$participant_id = intval($_GET['participant_id'] ?? 0);
$tab = sanitize_text_field($_GET['tab'] ?? 'info');
$action = sanitize_text_field($_GET['action'] ?? '');
$edit_id = intval($_GET['id'] ?? 0);

// Load participant
$participant = $this->participants_repo->get_participant_by_id($participant_id);
$plans = $this->plans_repo->get_plans_by_participant($participant_id);
$goals = $this->goals_repo->get_goals_by_participant($participant_id);
$notes = $this->notes_repo->get_notes_by_participant($participant_id);

// Load data for editing if applicable
$edit_item = null;
if ($edit_id && in_array($tab, ['plans', 'goals', 'notes'])) {
    switch ($tab) {
        case 'plans':
            $edit_item = $this->plans_repo->get_plan_by_id($edit_id);
            break;
        case 'goals':
            $edit_item = $this->goals_repo->get_goal_by_id($edit_id);
            break;
        case 'notes':
            $edit_item = $this->notes_repo->get_note_by_id($edit_id);
            break;
    }
}

// Enqueue DataTables
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
jQuery(document).ready(function($){
    $('.careplan-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            {
                extend: 'print',
                title: 'Participant Data'
            }
        ]
    });
});
</script>

<div class="wrap">
    <h1><?php echo esc_html($participant['first_name'] . ' ' . $participant['last_name']); ?></h1>

    <!-- Tabs -->
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant_id . '&tab=info'); ?>" class="nav-tab <?php echo $tab === 'info' ? 'nav-tab-active' : ''; ?>"><?php _e('Info', 'careplan'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant_id . '&tab=plans'); ?>" class="nav-tab <?php echo $tab === 'plans' ? 'nav-tab-active' : ''; ?>"><?php _e('Plans', 'careplan'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant_id . '&tab=goals'); ?>" class="nav-tab <?php echo $tab === 'goals' ? 'nav-tab-active' : ''; ?>"><?php _e('Goals', 'careplan'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant_id . '&tab=notes'); ?>" class="nav-tab <?php echo $tab === 'notes' ? 'nav-tab-active' : ''; ?>"><?php _e('Notes', 'careplan'); ?></a>
    </h2>

    <div class="tab-content" style="margin-top:20px;">

        <!-- Info Tab -->
        <?php if ($tab === 'info'): ?>
            <h2><?php _e('Participant Info', 'careplan'); ?></h2>
            <table class="widefat fixed">
                <tr><th><?php _e('First Name', 'careplan'); ?></th><td><?php echo esc_html($participant['first_name']); ?></td></tr>
                <tr><th><?php _e('Last Name', 'careplan'); ?></th><td><?php echo esc_html($participant['last_name']); ?></td></tr>
                <tr><th><?php _e('NDIS Number', 'careplan'); ?></th><td><?php echo esc_html($participant['ndis_number']); ?></td></tr>
                <tr><th><?php _e('Date of Birth', 'careplan'); ?></th><td><?php echo esc_html($participant['date_of_birth']); ?></td></tr>
            </table>

        <!-- Plans Tab -->
        <?php elseif ($tab === 'plans'): ?>
            <h2><?php _e('Plans', 'careplan'); ?></h2>
            <?php include CAREPLAN_PATH . 'includes/admin/templates/inline-form-plan.php'; ?>

            <table class="widefat fixed striped careplan-table">
                <thead>
                    <tr>
                        <th><?php _e('Start', 'careplan'); ?></th>
                        <th><?php _e('End', 'careplan'); ?></th>
                        <th><?php _e('Budget', 'careplan'); ?></th>
                        <th><?php _e('Status', 'careplan'); ?></th>
                        <th><?php _e('Actions', 'careplan'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($plans): foreach ($plans as $plan): ?>
                        <tr>
                            <td><?php echo esc_html($plan['plan_start']); ?></td>
                            <td><?php echo esc_html($plan['plan_end']); ?></td>
                            <td><?php echo esc_html($plan['total_budget']); ?></td>
                            <td><?php echo esc_html($plan['status']); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant_id . '&tab=plans&action=edit&id=' . $plan['id']); ?>"><?php _e('Edit', 'careplan'); ?></a> | 
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=careplan_delete_plan&id=' . $plan['id'] . '&participant_id=' . $participant_id), 'careplan_delete_plan'); ?>" onclick="return confirm('Are you sure?');"><?php _e('Delete', 'careplan'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5"><?php _e('No plans found.', 'careplan'); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <!-- Goals Tab -->
        <?php elseif ($tab === 'goals'): ?>
            <h2><?php _e('Goals', 'careplan'); ?></h2>
            <?php include CAREPLAN_PATH . 'includes/admin/templates/inline-form-goal.php'; ?>

            <table class="widefat fixed striped careplan-table">
                <thead>
                    <tr>
                        <th><?php _e('Title', 'careplan'); ?></th>
                        <th><?php _e('Description', 'careplan'); ?></th>
                        <th><?php _e('Priority', 'careplan'); ?></th>
                        <th><?php _e('Status', 'careplan'); ?></th>
                        <th><?php _e('Actions', 'careplan'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($goals): foreach ($goals as $goal): ?>
                        <tr>
                            <td><?php echo esc_html($goal['goal_title']); ?></td>
                            <td><?php echo esc_html($goal['goal_description']); ?></td>
                            <td><?php echo esc_html($goal['priority']); ?></td>
                            <td><?php echo esc_html($goal['status']); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant_id . '&tab=goals&action=edit&id=' . $goal['id']); ?>"><?php _e('Edit', 'careplan'); ?></a> | 
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=careplan_delete_goal&id=' . $goal['id'] . '&participant_id=' . $participant_id), 'careplan_delete_goal'); ?>" onclick="return confirm('Are you sure?');"><?php _e('Delete', 'careplan'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5"><?php _e('No goals found.', 'careplan'); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <!-- Notes Tab -->
        <?php elseif ($tab === 'notes'): ?>
            <h2><?php _e('Notes', 'careplan'); ?></h2>
            <?php include CAREPLAN_PATH . 'includes/admin/templates/inline-form-note.php'; ?>

            <table class="widefat fixed striped careplan-table">
                <thead>
                    <tr>
                        <th><?php _e('Note', 'careplan'); ?></th>
                        <th><?php _e('Created By', 'careplan'); ?></th>
                        <th><?php _e('Created At', 'careplan'); ?></th>
                        <th><?php _e('Actions', 'careplan'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($notes): foreach ($notes as $note): ?>
                        <tr>
                            <td><?php echo esc_html($note['note']); ?></td>
                            <td><?php echo esc_html(get_userdata($note['created_by'])->display_name ?? ''); ?></td>
                            <td><?php echo esc_html($note['created_at']); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant_id . '&tab=notes&action=edit&id=' . $note['id']); ?>"><?php _e('Edit', 'careplan'); ?></a> | 
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=careplan_delete_note&id=' . $note['id'] . '&participant_id=' . $participant_id), 'careplan_delete_note'); ?>" onclick="return confirm('Are you sure?');"><?php _e('Delete', 'careplan'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4"><?php _e('No notes found.', 'careplan'); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
