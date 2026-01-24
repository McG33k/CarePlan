<?php
defined('ABSPATH') || exit;

$action_url = admin_url('admin-post.php');

$note_id = $note->id ?? 0;
$goal_id = $note->goal_id ?? 0;
$note_text = $note->note ?? '';
$submit_text = $note_id ? __('Update Note', 'careplan') : __('Add Note', 'careplan');
?>

<div class="wrap" style="margin-top:20px;">
    <h2><?php echo esc_html($note_id ? __('Edit Note', 'careplan') : __('Add Note', 'careplan')); ?></h2>

    <form method="post" action="<?php echo esc_url($action_url); ?>">
        <?php wp_nonce_field('careplan_save_note'); ?>
        <input type="hidden" name="action" value="careplan_save_note">
        <input type="hidden" name="id" value="<?php echo esc_attr($note_id); ?>">

        <table class="form-table">

            <!-- Goal dropdown grouped by Participant -->
            <tr>
                <th><label for="goal_id"><?php esc_html_e('Goal', 'careplan'); ?></label></th>
                <td>
                    <select name="goal_id" id="goal_id" required>
                        <option value=""><?php esc_html_e('-- Select Goal --', 'careplan'); ?></option>

                        <?php
                        // Group goals by participant
                        $goals_by_participant = [];
                        foreach ($all_goals ?? [] as $goal) {
                            $plan = $goal->plan_id ? $this->plans_repo->get_plan($goal->plan_id) : null;
                            $participant_id_for_goal = $plan ? $plan->participant_id : 0;
                            if ($participant_id_for_goal) {
                                $goals_by_participant[$participant_id_for_goal][] = $goal;
                            }
                        }
                        ?>

                        <?php foreach ($participants as $participant) : ?>
                            <?php if (empty($goals_by_participant[$participant->id])) continue; ?>
                            <optgroup label="<?php echo esc_html($participant->first_name . ' ' . $participant->last_name); ?>">
                                <?php foreach ($goals_by_participant[$participant->id] as $goal) : ?>
                                    <option value="<?php echo esc_attr($goal->id); ?>" <?php selected($goal_id, $goal->id); ?>>
                                        <?php echo esc_html($goal->goal_title ?? 'Goal #' . $goal->id); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>

                    </select>
                </td>
            </tr>

            <!-- Note -->
            <tr>
                <th><label for="note"><?php esc_html_e('Note', 'careplan'); ?></label></th>
                <td>
                    <textarea name="note" id="note" rows="5" class="large-text" required><?php echo esc_textarea($note_text); ?></textarea>
                </td>
            </tr>

        </table>

        <?php submit_button($submit_text); ?>
    </form>
</div>
