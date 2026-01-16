<?php
defined('ABSPATH') || exit;

// Capability check
if (!current_user_can('manage_careplan')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}

// Sanitize input / existing $edit_item if editing
$plan_id = isset($edit_item->id) ? absint($edit_item->id) : 0;
$participant_id = isset($edit_item->participant_id) ? absint($edit_item->participant_id) : absint($participant_id ?? 0);
$plan_start = isset($edit_item->plan_start) ? esc_attr($edit_item->plan_start) : '';
$plan_end = isset($edit_item->plan_end) ? esc_attr($edit_item->plan_end) : '';
$total_budget = isset($edit_item->total_budget) ? esc_attr($edit_item->total_budget) : '';
$status = isset($edit_item->status) ? esc_attr($edit_item->status) : 'active';
?>

<div class="wrap" style="margin-top:20px;">
    <h2><?php echo $plan_id ? esc_html__('Edit Plan', 'careplan') : esc_html__('Add New Plan', 'careplan'); ?></h2>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('careplan_save_plan'); ?>
        <input type="hidden" name="action" value="careplan_save_plan">
        <input type="hidden" name="id" value="<?php echo $plan_id; ?>">
        <input type="hidden" name="participant_id" value="<?php echo $participant_id; ?>">

        <table class="form-table">
            <tr>
                <th><?php esc_html_e('Start Date', 'careplan'); ?></th>
                <td><input type="date" name="plan_start" value="<?php echo $plan_start; ?>" required></td>
            </tr>
            <tr>
                <th><?php esc_html_e('End Date', 'careplan'); ?></th>
                <td><input type="date" name="plan_end" value="<?php echo $plan_end; ?>" required></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Total Budget', 'careplan'); ?></th>
                <td><input type="number" step="0.01" name="total_budget" value="<?php echo $total_budget; ?>" required></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Status', 'careplan'); ?></th>
                <td>
                    <select name="status">
                        <option value="active" <?php selected($status, 'active'); ?>><?php esc_html_e('Active', 'careplan'); ?></option>
                        <option value="inactive" <?php selected($status, 'inactive'); ?>><?php esc_html_e('Inactive', 'careplan'); ?></option>
                    </select>
                </td>
            </tr>
        </table>

        <?php submit_button($plan_id ? __('Update Plan', 'careplan') : __('Add Plan', 'careplan')); ?>
    </form>
</div>
