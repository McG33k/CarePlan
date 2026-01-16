<<<<<<< HEAD
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
=======
<?php
defined('ABSPATH') || exit;

// Capability check
if (!current_user_can('manage_careplan')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}

// Sanitize input / existing $edit_item if editing
$goal_id = isset($edit_item->id) ? absint($edit_item->id) : 0;
$participant_id = isset($edit_item->participant_id) ? absint($edit_item->participant_id) : absint($participant_id ?? 0);
$goal_title = isset($edit_item->goal_title) ? esc_attr($edit_item->goal_title) : '';
$goal_description = isset($edit_item->goal_description) ? esc_textarea($edit_item->goal_description) : '';
$priority = isset($edit_item->priority) ? absint($edit_item->priority) : 0;
$status = isset($edit_item->status) ? esc_attr($edit_item->status) : 'open';
?>

<div class="wrap" style="margin-top:20px;">
    <h2><?php echo $goal_id ? esc_html__('Edit Goal', 'careplan') : esc_html__('Add New Goal', 'careplan'); ?></h2>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('careplan_save_goal'); ?>
        <input type="hidden" name="action" value="careplan_save_goal">
        <input type="hidden" name="id" value="<?php echo $goal_id; ?>">
        <input type="hidden" name="participant_id" value="<?php echo $participant_id; ?>">

        <table class="form-table">
            <tr>
                <th><?php esc_html_e('Title', 'careplan'); ?></th>
                <td><input type="text" name="goal_title" value="<?php echo $goal_title; ?>" required maxlength="255"></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Description', 'careplan'); ?></th>
                <td><textarea name="goal_description" rows="4" class="large-text"><?php echo $goal_description; ?></textarea></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Priority', 'careplan'); ?></th>
                <td><input type="number" name="priority" value="<?php echo $priority; ?>" min="0" max="10"></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Status', 'careplan'); ?></th>
                <td>
                    <select name="status">
                        <option value="open" <?php selected($status, 'open'); ?>><?php esc_html_e('Open', 'careplan'); ?></option>
                        <option value="completed" <?php selected($status, 'completed'); ?>><?php esc_html_e('Completed', 'careplan'); ?></option>
>>>>>>> 7ad5afa (Update Phase 1 structure)
                    </select>
                </td>
            </tr>
        </table>

<<<<<<< HEAD
        <?php submit_button(__('Save Goal', 'careplan')); ?>
    </form>
</div>
=======
        <?php submit_button($goal_id ? __('Update Goal', 'careplan') : __('Add Goal', 'careplan')); ?>
    </form>
</div>
    
>>>>>>> 7ad5afa (Update Phase 1 structure)
