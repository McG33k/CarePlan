<?php
defined('ABSPATH') || exit;

if (!current_user_can('manage_careplan')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Goals', 'careplan'); ?></h1>
    <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-goals&action=add')); ?>" class="page-title-action">
        <?php esc_html_e('Add New', 'careplan'); ?>
    </a>
    <hr class="wp-header-end">

    <?php if (!empty($goals)) : ?>
        <table id="careplan-goals-table" class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Participant', 'careplan'); ?></th>
                    <th><?php esc_html_e('Title', 'careplan'); ?></th>
                    <th><?php esc_html_e('Description', 'careplan'); ?></th>
                    <th><?php esc_html_e('Priority', 'careplan'); ?></th>
                    <th><?php esc_html_e('Status', 'careplan'); ?></th>
                    <th style="width:160px;"><?php esc_html_e('Actions', 'careplan'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($goals as $goal) : ?>
                    <tr>
                        <td><?php echo esc_html($goal->participant_name); ?></td>
                        <td><?php echo esc_html($goal->goal_title); ?></td>
                        <td><?php echo esc_html($goal->goal_description); ?></td>
                        <td><?php echo esc_html($goal->priority); ?></td>
                        <td><?php echo esc_html(ucfirst($goal->status)); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-goals&action=edit&id=' . absint($goal->id))); ?>">
                                <?php esc_html_e('Edit', 'careplan'); ?>
                            </a> |
                            <a href="<?php echo esc_url(
                                wp_nonce_url(
                                    admin_url('admin-post.php?action=careplan_delete_goal&id=' . absint($goal->id)),
                                    'careplan_delete_goal'
                                )
                            ); ?>"
                               onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this goal?', 'careplan')); ?>');">
                                <?php esc_html_e('Delete', 'careplan'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <script>
        jQuery(document).ready(function($) {
            $('#careplan-goals-table').DataTable({
    "paging": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'copy',
            exportOptions: { columns: ':not(:last-child)' }
        },
        {
            extend: 'csv',
            exportOptions: { columns: ':not(:last-child)' }
        },
        {
            extend: 'excel',
            exportOptions: { columns: ':not(:last-child)' }
        },
        {
            extend: 'pdf',
            exportOptions: { columns: ':not(:last-child)' }
        },
        {
            extend: 'print',
            exportOptions: { columns: ':not(:last-child)' }
        }
    ]
});

        });
        </script>

    <?php else : ?>
        <p><?php esc_html_e('No goals found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
