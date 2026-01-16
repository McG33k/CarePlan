<?php
defined('ABSPATH') || exit;

// Capability check
if (!current_user_can('manage_careplan')) {
    wp_die(__('You do not have permission to access this page.', 'careplan'));
}

// Sanitize input / existing $edit_item if editing
$note_id = isset($edit_item->id) ? absint($edit_item->id) : 0;
$participant_id = isset($edit_item->participant_id) ? absint($edit_item->participant_id) : absint($participant_id ?? 0);
$note_content = isset($edit_item->note) ? esc_textarea($edit_item->note) : '';
$created_by = $note_id ? absint($edit_item->created_by) : get_current_user_id();
?>

<div class="wrap" style="margin-top:20px;">
    <h2><?php echo $note_id ? esc_html__('Edit Note', 'careplan') : esc_html__('Add New Note', 'careplan'); ?></h2>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('careplan_save_note'); ?>
        <input type="hidden" name="action" value="careplan_save_note">
        <input type="hidden" name="id" value="<?php echo $note_id; ?>">
        <input type="hidden" name="participant_id" value="<?php echo $participant_id; ?>">
        <input type="hidden" name="created_by" value="<?php echo $created_by; ?>">

        <table class="form-table">
            <tr>
                <th><?php esc_html_e('Note', 'careplan'); ?></th>
                <td>
                    <textarea name="note" rows="4" class="large-text" required><?php echo $note_content; ?></textarea>
                </td>
            </tr>
        </table>

        <?php submit_button($note_id ? __('Update Note', 'careplan') : __('Add Note', 'careplan')); ?>
    </form>
</div>
