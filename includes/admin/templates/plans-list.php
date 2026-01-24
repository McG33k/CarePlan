<?php
defined('ABSPATH') || exit;

if (!current_user_can('manage_careplan')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}
?>

<script>
jQuery(document).ready(function($){
    $('.careplan-table').DataTable({
        responsive: true,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'excelHtml5', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'csvHtml5', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'pdfHtml5', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'print', exportOptions: { columns: ':not(:last-child)' } }
        ]
    });
});
</script>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Plans', 'careplan'); ?></h1>

    <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-plans&action=add')); ?>" class="page-title-action">
        <?php esc_html_e('Add New', 'careplan'); ?>
    </a>

    <hr class="wp-header-end">

    <?php if (!empty($plans)) : ?>
        <table class="widefat fixed striped careplan-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Plan Title', 'careplan'); ?></th>
                    <th><?php esc_html_e('Participant', 'careplan'); ?></th>
                    <th><?php esc_html_e('Start Date', 'careplan'); ?></th>
                    <th><?php esc_html_e('End Date', 'careplan'); ?></th>
                    <th><?php esc_html_e('Budget', 'careplan'); ?></th>
                    <th><?php esc_html_e('Status', 'careplan'); ?></th>
                    <th style="width:160px;"><?php esc_html_e('Actions', 'careplan'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan) : ?>
                    <tr>
                        <td><?php echo esc_html($plan->plan_title ?? ''); ?></td>
                        <td><?php echo esc_html($plan->participant_name ?? ''); ?></td>
                        <td><?php echo esc_html($plan->plan_start); ?></td>
                        <td><?php echo esc_html($plan->plan_end); ?></td>
                        <td><?php echo esc_html(number_format((float) $plan->total_budget, 2)); ?></td>
                        <td><?php echo esc_html($plan->status); ?></td>
                        <td>
                            <a href="<?php echo esc_url(
                                admin_url('admin.php?page=careplan-plans&action=edit&id=' . absint($plan->id))
                            ); ?>">
                                <?php esc_html_e('Edit', 'careplan'); ?>
                            </a> |
                            <a href="<?php echo esc_url(
                                wp_nonce_url(
                                    admin_url('admin-post.php?action=careplan_delete_plan&id=' . absint($plan->id)),
                                    'careplan_delete_plan'
                                )
                            ); ?>"
                            onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this plan?', 'careplan')); ?>');">
                                <?php esc_html_e('Delete', 'careplan'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p><?php esc_html_e('No plans found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
