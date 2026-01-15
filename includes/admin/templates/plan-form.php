<div class="wrap">
    <h1><?php _e('Add / Edit Plan', 'careplan'); ?></h1>
    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <?php wp_nonce_field('careplan_save_plan'); ?>
        <input type="hidden" name="action" value="careplan_save_plan">
        <input type="hidden" name="id" value="<?php echo esc_attr($plan->id ?? 0); ?>">

        <table class="form-table">
            <tr>
                <th><?php _e('Participant ID', 'careplan'); ?></th>
                <td><input type="number" name="participant_id" value="<?php echo esc_attr($plan->participant_id ?? ''); ?>" required></td>
            </tr>
            <tr>
                <th><?php _e('Start Date', 'careplan'); ?></th>
                <td><input type="date" name="plan_start" value="<?php echo esc_attr($plan->plan_start ?? ''); ?>"></td>
            </tr>
            <tr>
                <th><?php _e('End Date', 'careplan'); ?></th>
                <td><input type="date" name="plan_end" value="<?php echo esc_attr($plan->plan_end ?? ''); ?>"></td>
            </tr>
            <tr>
                <th><?php _e('Total Budget', 'careplan'); ?></th>
                <td><input type="number" step="0.01" name="total_budget" value="<?php echo esc_attr($plan->total_budget ?? ''); ?>"></td>
            </tr>
            <tr>
                <th><?php _e('Status', 'careplan'); ?></th>
                <td>
                    <select name="status">
                        <option value="active" <?php selected($plan->status ?? '', 'active'); ?>><?php _e('Active', 'careplan'); ?></option>
                        <option value="inactive" <?php selected($plan->status ?? '', 'inactive'); ?>><?php _e('Inactive', 'careplan'); ?></option>
                    </select>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Plan', 'careplan')); ?>
    </form>
</div>
