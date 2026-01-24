<?php
defined('ABSPATH') || exit;

// Capability check
if (!current_user_can('manage_careplan')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}

// Ensure variables are defined (prevents notices)
$goal_id          = $goal_id ?? 0;
$plan_id          = $plan_id ?? '';
$goal_title       = $goal_title ?? '';
$goal_description = $goal_description ?? '';
$priority         = $priority ?? 0;
$status           = $status ?? 'open';
?>

<div class="wrap" style="margin-top:20px;">
    <h2>
        <?php echo $goal_id
            ? esc_html__('Edit Goal', 'careplan')
            : esc_html__('Add Goal', 'careplan'); ?>
    </h2>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('careplan_save_goal'); ?>

        <input type="hidden" name="action" value="careplan_save_goal">
        <input type="hidden" name="id" value="<?php echo esc_attr($goal_id); ?>">

        <table class="form-table">

            <!-- Plan dropdown -->
            <tr>
                <th>
                    <label for="plan_id"><?php esc_html_e('Plan', 'careplan'); ?></label>
                </th>
                <td>
                    <select name="plan_id" id="plan_id" required>
                        <option value="">
                            <?php esc_html_e('-- Select Plan --', 'careplan'); ?>
                        </option>

                        <?php foreach ($participants as $participant) : ?>
                            <?php
                            $participant_plans = array_filter(
                                $all_plans,
                                fn($p) => (int) $p->participant_id === (int) $participant->id
                            );

                            if (empty($participant_plans)) {
                                continue;
                            }
                            ?>

                            <optgroup label="<?php echo esc_attr(
                                trim($participant->first_name . ' ' . $participant->last_name)
                            ); ?>">

                                <?php foreach ($participant_plans as $plan) : ?>
                                    <option value="<?php echo esc_attr($plan->id); ?>"
                                        <?php selected($plan_id, $plan->id); ?>>

                                        <?php
                                        echo esc_html(
                                            !empty($plan->plan_title)
                                                ? $plan->plan_title
                                                : sprintf(__('Plan #%d', 'careplan'), $plan->id)
                                        );
                                        ?>
                                    </option>
                                <?php endforeach; ?>

                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <!-- Goal Title -->
            <tr>
                <th>
                    <label for="goal_title"><?php esc_html_e('Goal Title', 'careplan'); ?></label>
                </th>
                <td>
                    <input
                        type="text"
                        name="goal_title"
                        id="goal_title"
                        class="regular-text"
                        value="<?php echo esc_attr($goal_title); ?>"
                        required
                        maxlength="255"
                    >
                </td>
            </tr>

            <!-- Goal Description -->
            <tr>
                <th>
                    <label for="goal_description"><?php esc_html_e('Description', 'careplan'); ?></label>
                </th>
                <td>
                    <textarea
                        name="goal_description"
                        id="goal_description"
                        rows="4"
                        class="large-text"
                    ><?php echo esc_textarea($goal_description); ?></textarea>
                </td>
            </tr>

            <!-- Priority -->
            <tr>
                <th>
                    <label for="priority"><?php esc_html_e('Priority', 'careplan'); ?></label>
                </th>
                <td>
                    <input
                        type="number"
                        name="priority"
                        id="priority"
                        value="<?php echo esc_attr($priority); ?>"
                        min="0"
                        max="10"
                    >
                </td>
            </tr>

            <!-- Status -->
            <tr>
                <th>
                    <label for="status"><?php esc_html_e('Status', 'careplan'); ?></label>
                </th>
                <td>
                    <select name="status" id="status">
                        <option value="open" <?php selected($status, 'open'); ?>>
                            <?php esc_html_e('Open', 'careplan'); ?>
                        </option>
                        <option value="completed" <?php selected($status, 'completed'); ?>>
                            <?php esc_html_e('Completed', 'careplan'); ?>
                        </option>
                    </select>
                </td>
            </tr>

        </table>

        <?php submit_button(
            $goal_id
                ? __('Update Goal', 'careplan')
                : __('Add Goal', 'careplan')
        ); ?>
    </form>
</div>
