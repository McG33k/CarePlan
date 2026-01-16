<?php
defined('ABSPATH') || exit;

// Capability check
if (!current_user_can('manage_careplan')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}

// Sanitize input
$participant_id = absint($_GET['participant_id'] ?? 0);
$tab            = sanitize_text_field($_GET['tab'] ?? 'info');
$action         = sanitize_text_field($_GET['action'] ?? '');
$edit_id        = absint($_GET['id'] ?? 0);

// Load participant
$participant = $this->participants_repo->get_participant($participant_id);
if (!$participant) {
    echo '<div class="notice notice-error"><p>' . esc_html__('Participant not found.', 'careplan') . '</p></div>';
    return;
}

// Load related data
$plans = $this->plans_repo->get_all_plans($participant_id);
$goals = $this->goals_repo->get_all_goals($participant_id);
$notes = $this->notes_repo->get_all_notes($participant_id);

// Load item to edit if applicable
$edit_item = null;
if ($edit_id && in_array($tab, ['plans', 'goals', 'notes'], true)) {
    switch ($tab) {
        case 'plans':
            $edit_item = $this->plans_repo->get_plan($edit_id);
            break;
        case 'goals':
            $edit_item = $this->goals_repo->get_goal($edit_id);
            break;
        case 'notes':
            $edit_item = $this->notes_repo->get_note($edit_id);
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
                title: '<?php echo esc_js($participant->first_name . ' ' . $participant->last_name); ?>'
            }
        ]
    });
});
</script>

<div class="wrap">
    <h1><?php echo esc_html($participant->first_name . ' ' . $participant->last_name); ?></h1>

    <!-- Tabs -->
    <h2 class="nav-tab-wrapper">
        <?php
        $tabs = ['info' => __('Info', 'careplan'), 'plans' => __('Plans', 'careplan'), 'goals' => __('Goals', 'careplan'), 'notes' => __('Notes', 'careplan')];
        foreach ($tabs as $key => $label):
        ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant_id . '&tab=' . $key)); ?>" class="nav-tab <?php echo $tab === $key ? 'nav-tab-active' : ''; ?>">
                <?php echo esc_html($label); ?>
            </a>
        <?php endforeach; ?>
    </h2>

    <div class="tab-content" style="margin-top:20px;">

        <!-- Info Tab -->
        <?php if ($tab === 'info'): ?>
            <h2><?php esc_html_e('Participant Info', 'careplan'); ?></h2>
            <table class="widefat fixed striped">
                <tr><th><?php esc_html_e('First Name', 'careplan'); ?></th><td><?php echo esc_html($participant->first_name); ?></td></tr>
                <tr><th><?php esc_html_e('Last Name', 'careplan'); ?></th><td><?php echo esc_html($participant->last_name); ?></td></tr>
                <tr><th><?php esc_html_e('NDIS Number', 'careplan'); ?></th><td><?php echo esc_html($participant->ndis_number); ?></td></tr>
                <tr><th><?php esc_html_e('Date of Birth', 'careplan'); ?></th><td><?php echo esc_html($participant->date_of_birth); ?></td></tr>
            </table>

        <!-- Plans Tab -->
        <?php elseif ($tab === 'plans'): ?>
            <h2><?php esc_html_e('Plans', 'careplan'); ?></h2>
            <?php include CAREPLAN_PATH . 'includes/admin/templates/plan-form.php'; ?>
            <?php include CAREPLAN_PATH . 'includes/admin/templates/plans-list.php'; ?>

        <!-- Goals Tab -->
        <?php elseif ($tab === 'goals'): ?>
            <h2><?php esc_html_e('Goals', 'careplan'); ?></h2>
            <?php include CAREPLAN_PATH . 'includes/admin/templates/goal-form.php'; ?>
            <?php include CAREPLAN_PATH . 'includes/admin/templates/goals-list.php'; ?>

        <!-- Notes Tab -->
        <?php elseif ($tab === 'notes'): ?>
            <h2><?php esc_html_e('Notes', 'careplan'); ?></h2>
            <?php include CAREPLAN_PATH . 'includes/admin/templates/notes-form.php'; ?>
            <?php include CAREPLAN_PATH . 'includes/admin/templates/notes-list.php'; ?>
        <?php endif; ?>

    </div>
</div>
