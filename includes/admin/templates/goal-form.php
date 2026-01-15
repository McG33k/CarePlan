<div class="wrap">
    <h1><?php _e('Add / Edit Goal', 'careplan'); ?></h1>
    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <?php wp_nonce_field('careplan_save_goal'); ?>
        <input type="hidden" name="action" value="careplan_save_goal">
        <input type="hidden" name="id" value="<?php echo esc_attr($goal->id ?? 0); ?>">

        <table class="form-table">
            <tr>
                <th><?php _e('Participant ID', 'careplan'); ?></th>
                <td><input type="number" name="participant_id" value="<?php echo esc_attr($goal->participant_id ?? ''); ?>" required></td>
            </tr>
            <tr>
                <th><?php _e('Title', 'careplan'); ?></th>
                <td><input type="text" name="goal_title" value="<?php echo esc_attr($goal->goal_title ?? ''); ?>" required></td>
            </tr>
            <tr>
                <th><?php _e('Description', 'careplan'); ?></th>
                <td><textarea name="goal_description" rows="4" class="large-text"><?php echo esc_textarea($goal->goal_description ?? ''); ?></textarea></td>
            </tr>
            <tr>
                <th><?php _e('Priority', 'careplan'); ?></th>
                <td><input type="number" name="priority" value="<?php echo esc_attr($goal->priority ?? 0); ?>"></td>
            </tr>
            <tr>
                <th><?php _e('Status', 'careplan'); ?></th>
                <td>
                    <select name="status">
                        <option value="open" <?php selected($goal->status ?? '', 'open'); ?>><?php _e('Open', 'careplan'); ?></option>
                        <option value="completed" <?php selected($goal->status ?? '', 'completed'); ?>><?php _e('Completed', 'careplan'); ?></option>
                    </select>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Goal', 'careplan')); ?>
    </form>
</div>
