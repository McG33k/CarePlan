<?php
defined('ABSPATH') || exit;

$action_url = admin_url('admin-post.php');

$id = $participant->id ?? 0;
$first_name = $participant->first_name ?? '';
$last_name  = $participant->last_name ?? '';
$ndis_number = $participant->ndis_number ?? '';
$date_of_birth = $participant->date_of_birth ?? '';
$submit_text = $id ? __('Update Participant', 'careplan') : __('Add Participant', 'careplan');
?>

<div class="wrap">
    <h1><?php echo esc_html($id ? __('Edit Participant', 'careplan') : __('Add Participant', 'careplan')); ?></h1>

    <form method="post" action="<?php echo esc_url($action_url); ?>">
        <?php wp_nonce_field('careplan_save_participant'); ?>
        <input type="hidden" name="action" value="careplan_save_participant">
        <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">

        <table class="form-table">
            <tr>
                <th><label for="first_name"><?php esc_html_e('First Name', 'careplan'); ?></label></th>
                <td><input type="text" name="first_name" id="first_name" class="regular-text" required value="<?php echo esc_attr($first_name); ?>"></td>
            </tr>
            <tr>
                <th><label for="last_name"><?php esc_html_e('Last Name', 'careplan'); ?></label></th>
                <td><input type="text" name="last_name" id="last_name" class="regular-text" required value="<?php echo esc_attr($last_name); ?>"></td>
            </tr>
            <tr>
                <th><label for="ndis_number"><?php esc_html_e('NDIS Number', 'careplan'); ?></label></th>
                <td><input type="text" name="ndis_number" id="ndis_number" class="regular-text" value="<?php echo esc_attr($ndis_number); ?>"></td>
            </tr>
            <tr>
                <th><label for="date_of_birth"><?php esc_html_e('Date of Birth', 'careplan'); ?></label></th>
                <td><input type="date" name="date_of_birth" id="date_of_birth" class="regular-text" value="<?php echo esc_attr($date_of_birth); ?>"></td>
            </tr>
        </table>

        <?php submit_button($submit_text); ?>
    </form>
</div>
