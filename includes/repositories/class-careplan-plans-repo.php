<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';

/**
 * Repository for CarePlan Plans
 */
class CarePlan_Plans_Repo extends CarePlan_Repo {

    public function __construct() {
    global $wpdb;
    $this->wpdb  = $wpdb;
    $this->table = $this->wpdb->prefix . 'careplan_plans';
}


    /**
     * Create a new plan
     *
     * @param array $data
     * @return int|false Insert ID on success, false on failure
     */
    public function create_plan( $data ) {
        $defaults = [
            'participant_id' => 0,
            'plan_start'     => null,
            'plan_end'       => null,
            'total_budget'   => 0,
            'status'         => 'active',
        ];

        $data = wp_parse_args( $data, $defaults );

        // Sanitize inputs
        $data['participant_id'] = absint( $data['participant_id'] );
        $data['plan_start']     = sanitize_text_field( $data['plan_start'] );
        $data['plan_end']       = sanitize_text_field( $data['plan_end'] );
        $data['total_budget']   = floatval( $data['total_budget'] );
        $data['status']         = sanitize_text_field( $data['status'] );

        return $this->insert( $data, ['%d','%s','%s','%f','%s'] );
    }

    /**
     * Update plan by ID
     *
     * @param int $id
     * @param array $data
     * @return int|false Number of rows affected or false
     */
    public function update_plan( $id, $data ) {
        $id = absint( $id );

        // Sanitize inputs if set
        if ( isset( $data['participant_id'] ) ) {
            $data['participant_id'] = absint( $data['participant_id'] );
        }
        if ( isset( $data['plan_start'] ) ) {
            $data['plan_start'] = sanitize_text_field( $data['plan_start'] );
        }
        if ( isset( $data['plan_end'] ) ) {
            $data['plan_end'] = sanitize_text_field( $data['plan_end'] );
        }
        if ( isset( $data['total_budget'] ) ) {
            $data['total_budget'] = floatval( $data['total_budget'] );
        }
        if ( isset( $data['status'] ) ) {
            $data['status'] = sanitize_text_field( $data['status'] );
        }

        return $this->update( $data, ['id' => $id], ['%d','%s','%s','%f','%s'], ['%d'] );
    }

    /**
     * Delete plan by ID
     *
     * @param int $id
     * @return int|false Rows affected or false
     */
    public function delete_plan( $id ) {
        $id = absint( $id );
        return $this->delete( ['id' => $id], ['%d'] );
    }

    /**
     * Get plan by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_plan( $id ) {
        $id = absint( $id );
        $result = $this->get( ['id' => $id] );
        return $result ?: null;
    }

    /**
     * Get all plans
     *
     * @param int|null $participant_id Optional participant ID filter
     * @return array Array of plan objects
     */
    public function get_all_plans( $participant_id = null ) {
        if ( $participant_id ) {
            $participant_id = absint( $participant_id );
            $results = $this->get_all( ['participant_id' => $participant_id] );
        } else {
            $results = $this->get_all();
        }
        return $results ?: [];
    }
}
