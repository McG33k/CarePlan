<div class="wrap">
    <h1><?php _e('Notes', 'careplan'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=careplan-notes&action=add'); ?>" class="page-title-action"><?php _e('Add New', 'careplan'); ?></a>

    <?php if (!empty($notes)): ?>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('ID', 'careplan'); ?></th>
                    <th><?php _e('Participant ID', 'careplan'); ?></th>
                    <th><?php _e('Note', 'careplan'); ?></th>
                    <th><?php _e('Created By', 'careplan'); ?></th>
                    <th><?php _e('Created At', 'careplan'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                    <tr>
                        <td><?php echo esc_html($note->id); ?></td>
                        <td><?php echo esc_html($note->participant_id); ?></td>
                        <td><?php echo esc_html($note->note); ?></td>
                        <td><?php echo esc_html($note->created_by); ?></td>
                        <td><?php echo esc_html($note->created_at); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><?php _e('No notes found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
