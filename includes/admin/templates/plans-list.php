<div class="wrap">
    <h1><?php _e('Plans', 'careplan'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=careplan-plans&action=add'); ?>" class="page-title-action"><?php _e('Add New', 'careplan'); ?></a>

    <?php if (!empty($plans)): ?>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('ID', 'careplan'); ?></th>
                    <th><?php _e('Participant ID', 'careplan'); ?></th>
                    <th><?php _e('Start Date', 'careplan'); ?></th>
                    <th><?php _e('End Date', 'careplan'); ?></th>
                    <th><?php _e('Total Budget', 'careplan'); ?></th>
                    <th><?php _e('Status', 'careplan'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan): ?>
                    <tr>
                        <td><?php echo esc_html($plan->id); ?></td>
                        <td><?php echo esc_html($plan->participant_id); ?></td>
                        <td><?php echo esc_html($plan->plan_start); ?></td>
                        <td><?php echo esc_html($plan->plan_end); ?></td>
                        <td><?php echo esc_html($plan->total_budget); ?></td>
                        <td><?php echo esc_html($plan->status); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><?php _e('No plans found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
