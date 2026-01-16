<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';

/**
 * Repository for CarePlan Goals
 */
class CarePlan_Goals_Repo extends CarePlan_Repo {

    public function __construct() {
    global $wpdb;
    $this->wpdb  = $wpdb;
    $this->table = $this->wpdb->prefix . 'careplan_goals';
}


    /**
     * Create a new goal
     *
     * @param array $data
     * @return int|false Insert ID on success, false on failure
     */
    public function create_goal( $data ) {
        $defaults = [
            'participant_id'   => 0,
            'goal_title'       => '',
            'goal_description' => '',
            'priority'         => 0,
            'status'           => 'open',
        ];

        $data = wp_parse_args( $data, $defaults );

        // Sanitize inputs
        $data['participant_id']   = absint( $data['participant_id'] );
        $data['goal_title']       = sanitize_text_field( $data['goal_title'] );
        $data['goal_description'] = sanitize_textarea_field( $data['goal_description'] );
        $data['priority']         = intval( $data['priority'] );
        $data['status']           = sanitize_text_field( $data['status'] );

        return $this->insert( $data, ['%d','%s','%s','%d','%s'] );
    }

    /**
     * Update goal by ID
     *
     * @param int $id
     * @param array $data
     * @return int|false Number of rows affected or false
     */
    public function update_goal( $id, $data ) {
        $id = absint( $id );

        // Sanitize inputs if set
        if ( isset( $data['participant_id'] ) ) {
            $data['participant_id'] = absint( $data['participant_id'] );
        }
        if ( isset( $data['goal_title'] ) ) {
            $data['goal_title'] = sanitize_text_field( $data['goal_title'] );
        }
        if ( isset( $data['goal_description'] ) ) {
            $data['goal_description'] = sanitize_textarea_field( $data['goal_description'] );
        }
        if ( isset( $data['priority'] ) ) {
            $data['priority'] = intval( $data['priority'] );
        }
        if ( isset( $data['status'] ) ) {
            $data['status'] = sanitize_text_field( $data['status'] );
        }

        return $this->update( $data, ['id' => $id], ['%d','%s','%s','%d','%s'], ['%d'] );
    }

    /**
     * Delete goal by ID
     *
     * @param int $id
     * @return int|false Rows affected or false
     */
    public function delete_goal( $id ) {
        $id = absint( $id );
        return $this->delete( ['id' => $id], ['%d'] );
    }

    /**
     * Get goal by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_goal( $id ) {
        $id = absint( $id );
        $result = $this->get( ['id' => $id] );
        return $result ?: null;
    }

    /**
     * Get all goals
     *
     * @param int|null $participant_id Optional participant ID filter
     * @return array Array of goal objects
     */
    public function get_all_goals( $participant_id = null ) {
        if ( $participant_id ) {
            $participant_id = absint( $participant_id );
            $results = $this->get_all( ['participant_id' => $participant_id] );
        } else {
            $results = $this->get_all();
        }
        return $results ?: [];
    }
}
