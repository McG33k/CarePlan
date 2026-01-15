<div class="wrap">
    <h1><?php _e('Participants', 'careplan'); ?></h1>

    <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
        <div id="message" class="updated notice is-dismissible">
            <p><?php _e('Participant saved successfully.', 'careplan'); ?></p>
        </div>
    <?php endif; ?>

    <a href="<?php echo admin_url('admin.php?page=careplan-dashboard&action=add'); ?>" class="page-title-action">
        <?php _e('Add New Participant', 'careplan'); ?>
    </a>

    <?php if (!empty($participants)): ?>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Name', 'careplan'); ?></th>
                    <th><?php _e('NDIS Number', 'careplan'); ?></th>
                    <th><?php _e('Date of Birth', 'careplan'); ?></th>
                    <th><?php _e('Actions', 'careplan'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participants as $participant): ?>
                    <tr>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant->id)); ?>">
                                <?php echo esc_html($participant->first_name . ' ' . $participant->last_name); ?>
                            </a>
                        </td>
                        <td><?php echo esc_html($participant->ndis_number); ?></td>
                        <td><?php echo esc_html($participant->date_of_birth); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-participant-detail&participant_id=' . $participant->id)); ?>">
                                <?php _e('View', 'careplan'); ?>
                            </a> | 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=careplan-dashboard&action=edit&id=' . $participant->id)); ?>">
                                <?php _e('Edit', 'careplan'); ?>
                            </a> | 
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=careplan_delete_participant&id=' . $participant->id), 'careplan_delete_participant')); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this participant?', 'careplan'); ?>');">
                                <?php _e('Delete', 'careplan'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><?php _e('No participants found.', 'careplan'); ?></p>
    <?php endif; ?>
</div>
