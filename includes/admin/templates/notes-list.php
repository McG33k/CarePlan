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
                title: '<?php echo esc_js(__('Notes Data', 'careplan')); ?>'
            }
        ]
    });
});
</script>

<div class="wrap">
    <h1><?php esc_html_e('Notes', 'careplan'); ?></h1>
    <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-notes&action=add')); ?>" class="page-title-action">
        <?php esc_html_e('Add New', 'careplan'); ?>
    </a>

    <?php if (!empty($notes)): ?>
        <table class="widefat fixed striped careplan-table" cellspacing="0">
            <thead>
                <tr>
                    <th><?php esc_html_e('ID', 'careplan'); ?></th>
                    <th><?php esc_html_e('Participant ID', 'careplan'); ?></th>
                    <th><?php esc_html_e('Note', 'careplan'); ?></th>
                    <th><?php esc_html_e('Created By', 'careplan'); ?></th>
                    <th><?php esc_html_e('Created At', 'careplan'); ?></th>
                    <th><?php esc_html_e('Actions', 'careplan'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                    <tr>
                        <td><?php echo esc_html(intval($note->id)); ?></td>
                        <td><?php echo esc_html(intval($note->participant_id)); ?></td>
                        <td><?php echo esc_html($note->note); ?></td>
                        <td><?php echo esc_html(intval($note->created_by)); ?></td>
                        <td><?php echo esc_html($note->created_at); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-notes&action=edit&id=' . intval($note->id))); ?>">
                                <?php esc_html_e('Edit', 'careplan'); ?>
                            </a> | 
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=careplan_delete_note&id=' . intval($note->id)), 'careplan_delete_note')); ?>" onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this note?', 'careplan')); ?>');">
                                <?php esc_html_e('Delete', 'careplan'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><?php esc_html_e('No notes found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
