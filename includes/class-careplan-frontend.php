<?php
defined('ABSPATH') || exit;

class CarePlan_Frontend {

    public function __construct() {
        add_shortcode('careplan_participant', [$this, 'render_participant']);
    }

    /**
     * Render participant info on frontend via shortcode.
     * Usage: [careplan_participant id="123"]
     */
    public function render_participant($atts) {
        global $wpdb;

        $atts = shortcode_atts([
            'id' => 0,
        ], $atts, 'careplan_participant');

        $participant_id = intval($atts['id']);
        if (!$participant_id) {
            return '<p>Participant not found.</p>';
        }

        // Fetch participant data
        $table = $wpdb->prefix . 'careplan_participants';
        $participant = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE id = %d",
            $participant_id
        ));

        if (!$participant) {
            return '<p>Participant not found.</p>';
        }

        ob_start();
        ?>
        <div class="careplan-participant">
            <h3><?php echo esc_html($participant->first_name . ' ' . $participant->last_name); ?></h3>
            <p><strong>NDIS Number:</strong> <?php echo esc_html($participant->ndis_number); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo esc_html($participant->date_of_birth); ?></p>
        </div>
        <?php
        return ob_get_clean();
    }
}
