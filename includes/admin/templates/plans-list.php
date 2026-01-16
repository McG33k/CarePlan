<?php
defined('ABSPATH') || exit;

// Capability check
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}

// Enqueue DataTables CSS/JS (CDN)
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
jQuery(document).ready(function($) {
    $('.careplan-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            {
                extend: 'print',
                title: '<?php echo esc_js(__('Plans Data', 'careplan')); ?>'
            }
        ]
    });
});
</script>

<div class="wrap">
    <h1><?php esc_html_e('Plans', 'careplan'); ?></h1>
    <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-plans&action=add')); ?>" class="page-title-action">
        <?php esc_html_e('Add New', 'careplan'); ?>
    </a>

    <?php if (!empty($plans)): ?>
        <table class="widefat fixed striped careplan-table" cellspacing="0">
            <thead>
                <tr>
                    <th><?php esc_html_e('ID', 'careplan'); ?></th>
                    <th><?php esc_html_e('Participant ID', 'careplan'); ?></th>
                    <th><?php esc_html_e('Start Date', 'careplan'); ?></th>
                    <th><?php esc_html_e('End Date', 'careplan'); ?></th>
                    <th><?php esc_html_e('Total Budget', 'careplan'); ?></th>
                    <th><?php esc_html_e('Status', 'careplan'); ?></th>
                    <th><?php esc_html_e('Actions', 'careplan'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan): ?>
                    <tr>
                        <td><?php echo esc_html(intval($plan->id)); ?></td>
                        <td><?php echo esc_html(intval($plan->participant_id)); ?></td>
                        <td><?php echo esc_html($plan->plan_start); ?></td>
                        <td><?php echo esc_html($plan->plan_end); ?></td>
                        <td><?php echo esc_html(number_format((float)$plan->total_budget, 2)); ?></td>
                        <td><?php echo esc_html($plan->status); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-plans&action=edit&id=' . intval($plan->id))); ?>">
                                <?php esc_html_e('Edit', 'careplan'); ?>
                            </a> | 
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=careplan_delete_plan&id=' . intval($plan->id)), 'careplan_delete_plan')); ?>" onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this plan?', 'careplan')); ?>');">
                                <?php esc_html_e('Delete', 'careplan'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><?php esc_html_e('No plans found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
