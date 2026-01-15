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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($goals as $goal): ?>
                    <tr>
                        <td><?php echo esc_html($goal->id); ?></td>
                        <td><?php echo esc_html($goal->participant_id); ?></td>
                        <td><?php echo esc_html($goal->goal_title); ?></td>
                        <td><?php echo esc_html($goal->goal_description); ?></td>
                        <td><?php echo esc_html($goal->priority); ?></td>
                        <td><?php echo esc_html($goal->status); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><?php _e('No goals found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
