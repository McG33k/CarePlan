<?php
defined('ABSPATH') || exit;

$action_url = admin_url('admin-post.php');

$id             = $plan->id ?? 0;
$participant_id = $plan->participant_id ?? '';
$plan_title     = $plan->plan_title ?? '';
$plan_start     = $plan->plan_start ?? '';
$plan_end       = $plan->plan_end ?? '';
$total_budget   = $plan->total_budget ?? '';
$status         = $plan->status ?? 'Active';
$submit_text    = $id ? __('Update Plan', 'careplan') : __('Add Plan', 'careplan');
?>

<div class="wrap">
    <h1><?php echo esc_html($id ? __('Edit Plan', 'careplan') : __('Add Plan', 'careplan')); ?></h1>

    <form method="post" action="<?php echo esc_url($action_url); ?>">
        <?php wp_nonce_field('careplan_save_plan'); ?>
        <input type="hidden" name="action" value="careplan_save_plan">
        <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">

        <table class="form-table">

            <!-- Participant -->
            <tr>
                <th>
                    <label for="participant_id"><?php esc_html_e('Participant', 'careplan'); ?></label>
                </th>
                <td>
                    <select name="participant_id" id="participant_id" required>
                        <option value=""><?php esc_html_e('-- Select Participant --', 'careplan'); ?></option>
                        <?php foreach ($participants as $participant) : ?>
                            <option value="<?php echo esc_attr($participant->id); ?>"
                                <?php selected($participant_id, $participant->id); ?>>
                                <?php echo esc_html($participant->first_name . ' ' . $participant->last_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <!-- ✅ PLAN TITLE (FIX) -->
            <tr>
                <th>
                    <label for="plan_title"><?php esc_html_e('Plan Title', 'careplan'); ?></label>
                </th>
                <td>
                    <input
                        type="text"
                        name="plan_title"
                        id="plan_title"
                        class="regular-text"
                        value="<?php echo esc_attr($plan_title); ?>"
                        required
                    >
                </td>
            </tr>

            <!-- Plan Start -->
            <tr>
                <th>
                    <label for="plan_start"><?php esc_html_e('Plan Start', 'careplan'); ?></label>
                </th>
                <td>
                    <input type="date" name="plan_start" id="plan_start"
                           value="<?php echo esc_attr($plan_start); ?>" required>
                </td>
            </tr>

            <!-- Plan End -->
            <tr>
                <th>
                    <label for="plan_end"><?php esc_html_e('Plan End', 'careplan'); ?></label>
                </th>
                <td>
                    <input type="date" name="plan_end" id="plan_end"
                           value="<?php echo esc_attr($plan_end); ?>" required>
                </td>
            </tr>

            <!-- Total Budget -->
            <tr>
                <th>
                    <label for="total_budget"><?php esc_html_e('Total Budget', 'careplan'); ?></label>
                </th>
                <td>
                    <input type="number" name="total_budget" id="total_budget"
                           value="<?php echo esc_attr($total_budget); ?>"
                           step="0.01" required>
                </td>
            </tr>

            <!-- Status -->
            <tr>
                <th>
                    <label for="status"><?php esc_html_e('Status', 'careplan'); ?></label>
                </th>
                <td>
                    <select name="status" id="status">
                        <option value="Active" <?php selected($status, 'Active'); ?>>Active</option>
                        <option value="Completed" <?php selected($status, 'Completed'); ?>>Completed</option>
                        <option value="Cancelled" <?php selected($status, 'Cancelled'); ?>>Cancelled</option>
                    </select>
                </td>
            </tr>

        </table>

        <?php submit_button($submit_text); ?>
    </form>
</div>
