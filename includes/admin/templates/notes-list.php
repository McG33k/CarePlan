<?php
defined('ABSPATH') || exit;

if (!current_user_can('manage_careplan')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}
?>

<!-- DataTables CSS & JS same as Plans List -->

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Notes', 'careplan'); ?></h1>
    <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-notes&action=add')); ?>" class="page-title-action">
    <?php esc_html_e('Add New', 'careplan'); ?>
</a>
<hr class="wp-header-end">

    <hr class="wp-header-end">

    <?php if (!empty($notes)) : ?>
        <table class="widefat fixed striped careplan-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Participant', 'careplan'); ?></th>
                    <th><?php esc_html_e('Note', 'careplan'); ?></th>
                    <th><?php esc_html_e('Created By', 'careplan'); ?></th>
                    <th><?php esc_html_e('Date', 'careplan'); ?></th>
                    <th style="width:160px;"><?php esc_html_e('Actions', 'careplan'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note) : ?>
                    <tr>
                        <td><?php echo esc_html($note->participant_name ?? ''); ?></td>
                        <td><?php echo esc_html($note->note); ?></td>
                        <td><?php echo esc_html($note->created_by_name ?? ''); ?></td>
                        <td><?php echo esc_html($note->created_at); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-notes&action=edit&id=' . absint($note->id))); ?>">
                                <?php esc_html_e('Edit', 'careplan'); ?>
                            </a> |
                            <a href="<?php echo esc_url(
                                wp_nonce_url(
                                    admin_url('admin-post.php?action=careplan_delete_note&id=' . absint($note->id)),
                                    'careplan_delete_note'
                                )
                            ); ?>"
                               onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this note?', 'careplan')); ?>');">
                                <?php esc_html_e('Delete', 'careplan'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p><?php esc_html_e('No notes found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
