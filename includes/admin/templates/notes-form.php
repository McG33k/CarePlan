<?php
defined('ABSPATH') || exit;

// Capability check
if (!current_user_can('manage_careplan')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}

// Editing existing note?
$note_id = isset($edit_item->id) ? absint($edit_item->id) : 0;

// Goal is REQUIRED
$goal_id = isset($edit_item->goal_id)
    ? absint($edit_item->goal_id)
    : absint($_GET['goal_id'] ?? 0);

if (!$goal_id) {
    wp_die(__('Invalid goal. Notes must be attached to a goal.', 'careplan'));
}

$note_content = isset($edit_item->note) ? esc_textarea($edit_item->note) : '';
$created_by   = $note_id
    ? absint($edit_item->created_by)
    : get_current_user_id();
?>

<div class="wrap" style="margin-top:20px;">
    <h2>
        <?php echo $note_id
            ? esc_html__('Edit Note', 'careplan')
            : esc_html__('Add Note', 'careplan'); ?>
    </h2>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('careplan_save_note'); ?>

        <input type="hidden" name="action" value="careplan_save_note">
        <input type="hidden" name="id" value="<?php echo esc_attr($note_id); ?>">
        <input type="hidden" name="goal_id" value="<?php echo esc_attr($goal_id); ?>">
        <input type="hidden" name="created_by" value="<?php echo esc_attr($created_by); ?>">

        <table class="form-table">
            <tr>
                <th>
                    <label for="note"><?php esc_html_e('Note', 'careplan'); ?></label>
                </th>
                <td>
                    <textarea
                        name="note"
                        id="note"
                        rows="5"
                        class="large-text"
                        required
                    ><?php echo $note_content; ?></textarea>
                </td>
            </tr>
        </table>

        <?php submit_button(
            $note_id
                ? __('Update Note', 'careplan')
                : __('Add Note', 'careplan')
        ); ?>
    </form>
</div>
