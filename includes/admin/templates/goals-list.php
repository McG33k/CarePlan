<<<<<<< HEAD
<div class="wrap">
    <h1><?php _e('Goals', 'careplan'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=careplan-goals&action=add'); ?>" class="page-title-action"><?php _e('Add New', 'careplan'); ?></a>

    <?php if (!empty($goals)): ?>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('ID', 'careplan'); ?></th>
                    <th><?php _e('Participant ID', 'careplan'); ?></th>
                    <th><?php _e('Title', 'careplan'); ?></th>
                    <th><?php _e('Description', 'careplan'); ?></th>
                    <th><?php _e('Priority', 'careplan'); ?></th>
                    <th><?php _e('Status', 'careplan'); ?></th>
=======
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
                title: '<?php echo esc_js(__('Goals Data', 'careplan')); ?>'
            }
        ]
    });
});
</script>

<div class="wrap">
    <h1><?php esc_html_e('Goals', 'careplan'); ?></h1>
    <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-goals&action=add')); ?>" class="page-title-action">
        <?php esc_html_e('Add New', 'careplan'); ?>
    </a>

    <?php if (!empty($goals)): ?>
        <table class="widefat fixed striped careplan-table" cellspacing="0">
            <thead>
                <tr>
                    <th><?php esc_html_e('ID', 'careplan'); ?></th>
                    <th><?php esc_html_e('Participant ID', 'careplan'); ?></th>
                    <th><?php esc_html_e('Title', 'careplan'); ?></th>
                    <th><?php esc_html_e('Description', 'careplan'); ?></th>
                    <th><?php esc_html_e('Priority', 'careplan'); ?></th>
                    <th><?php esc_html_e('Status', 'careplan'); ?></th>
                    <th><?php esc_html_e('Actions', 'careplan'); ?></th>
>>>>>>> 7ad5afa (Update Phase 1 structure)
                </tr>
            </thead>
            <tbody>
                <?php foreach ($goals as $goal): ?>
                    <tr>
<<<<<<< HEAD
                        <td><?php echo esc_html($goal->id); ?></td>
                        <td><?php echo esc_html($goal->participant_id); ?></td>
                        <td><?php echo esc_html($goal->goal_title); ?></td>
                        <td><?php echo esc_html($goal->goal_description); ?></td>
                        <td><?php echo esc_html($goal->priority); ?></td>
                        <td><?php echo esc_html($goal->status); ?></td>
=======
                        <td><?php echo esc_html(intval($goal->id)); ?></td>
                        <td><?php echo esc_html(intval($goal->participant_id)); ?></td>
                        <td><?php echo esc_html($goal->goal_title); ?></td>
                        <td><?php echo esc_html($goal->goal_description); ?></td>
                        <td><?php echo esc_html(intval($goal->priority)); ?></td>
                        <td><?php echo esc_html($goal->status); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-goals&action=edit&id=' . intval($goal->id))); ?>">
                                <?php esc_html_e('Edit', 'careplan'); ?>
                            </a> | 
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=careplan_delete_goal&id=' . intval($goal->id)), 'careplan_delete_goal')); ?>" onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this goal?', 'careplan')); ?>');">
                                <?php esc_html_e('Delete', 'careplan'); ?>
                            </a>
                        </td>
>>>>>>> 7ad5afa (Update Phase 1 structure)
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
<<<<<<< HEAD
        <p><?php _e('No goals found.', 'careplan'); ?></p>
=======
        <p><?php esc_html_e('No goals found.', 'careplan'); ?></p>
>>>>>>> 7ad5afa (Update Phase 1 structure)
    <?php endif; ?>
</div>
