<?php
defined('ABSPATH') || exit;

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}
?>

<!-- DataTables (move to enqueue later) -->
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
    responsive: true,
    pageLength: 10,
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'copyHtml5',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        },
        {
            extend: 'excelHtml5',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        },
        {
            extend: 'csvHtml5',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        },
        {
            extend: 'pdfHtml5',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        },
        {
            extend: 'print',
            title: '<?php echo esc_js(__('Participants', 'careplan')); ?>',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        }
    ]
});

});
</script>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Participants', 'careplan'); ?></h1>

    <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-dashboard&action=add')); ?>" class="page-title-action">
        <?php esc_html_e('Add New', 'careplan'); ?>
    </a>

    <hr class="wp-header-end">

    <?php if (!empty($participants)) : ?>
        <table class="widefat fixed striped careplan-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Name', 'careplan'); ?></th>
                    <th><?php esc_html_e('NDIS Number', 'careplan'); ?></th>
                    <th><?php esc_html_e('Date of Birth', 'careplan'); ?></th>
                    <th style="width:160px;"><?php esc_html_e('Actions', 'careplan'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participants as $participant) : ?>
                    <tr>
                        <td>
                            <strong>
                                <?php echo esc_html(trim($participant->first_name . ' ' . $participant->last_name)); ?>
                            </strong>
                        </td>
                        <td><?php echo esc_html($participant->ndis_number); ?></td>
                        <td><?php echo esc_html($participant->date_of_birth); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-dashboard&action=edit&id=' . absint($participant->id))); ?>">
                                <?php esc_html_e('Edit', 'careplan'); ?>
                            </a>
                            |
                            <a href="<?php echo esc_url(
                                wp_nonce_url(
                                    admin_url('admin-post.php?action=careplan_delete_participant&id=' . absint($participant->id)),
                                    'careplan_delete_participant'
                                )
                            ); ?>"
                               onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this participant?', 'careplan')); ?>');">
                                <?php esc_html_e('Delete', 'careplan'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p><?php esc_html_e('No participants found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
