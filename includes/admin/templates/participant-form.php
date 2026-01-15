<div class="wrap">
    <h1><?php _e('Add / Edit Participant', 'careplan'); ?></h1>
    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <?php wp_nonce_field('careplan_save_participant'); ?>
        <input type="hidden" name="action" value="careplan_save_participant">
        <input type="hidden" name="id" value="<?php echo esc_attr($participant->id ?? 0); ?>">

        <table class="form-table">
            <tr>
                <th><?php _e('First Name', 'careplan'); ?></th>
                <td><input type="text" name="first_name" value="<?php echo esc_attr($participant->first_name ?? ''); ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><?php _e('Last Name', 'careplan'); ?></th>
                <td><input type="text" name="last_name" value="<?php echo esc_attr($participant->last_name ?? ''); ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><?php _e('NDIS Number', 'careplan'); ?></th>
                <td><input type="text" name="ndis_number" value="<?php echo esc_attr($participant->ndis_number ?? ''); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><?php _e('Date of Birth', 'careplan'); ?></th>
                <td><input type="date" name="date_of_birth" value="<?php echo esc_attr($participant->date_of_birth ?? ''); ?>" class="regular-text"></td>
            </tr>
        </table>

        <?php submit_button(__('Save Participant', 'careplan')); ?>
    </form>
</div>
