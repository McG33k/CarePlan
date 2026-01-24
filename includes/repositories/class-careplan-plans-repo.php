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
            'plan_title'     => '', // ✅ NEW
            'plan_start'     => null,
            'plan_end'       => null,
            'total_budget'   => 0,
            'status'         => 'Active',
        ];

        $data = wp_parse_args( $data, $defaults );

        // Sanitize inputs
        $data['participant_id'] = absint( $data['participant_id'] );
        $data['plan_title']     = sanitize_text_field( $data['plan_title'] ); // ✅ NEW
        $data['plan_start']     = sanitize_text_field( $data['plan_start'] );
        $data['plan_end']       = sanitize_text_field( $data['plan_end'] );
        $data['total_budget']   = floatval( $data['total_budget'] );
        $data['status']         = sanitize_text_field( $data['status'] );

        return $this->insert(
            $data,
            ['%d','%s','%s','%s','%f','%s'] // ✅ UPDATED
        );
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

        if ( isset( $data['participant_id'] ) ) {
            $data['participant_id'] = absint( $data['participant_id'] );
        }
        if ( isset( $data['plan_title'] ) ) { // ✅ NEW
            $data['plan_title'] = sanitize_text_field( $data['plan_title'] );
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

        return $this->update(
            $data,
            ['id' => $id],
            ['%d','%s','%s','%s','%f','%s'], // ✅ UPDATED
            ['%d']
        );
    }

    /**
     * Delete plan by ID
     */
    public function delete_plan( $id ) {
        return $this->delete( ['id' => absint( $id )], ['%d'] );
    }

    /**
     * Get plan by ID
     */
    public function get_plan( $id ) {
        return $this->get( ['id' => absint( $id )] ) ?: null;
    }

    /**
     * Get all plans
     */
    public function get_all_plans() {
        global $wpdb;

        $plans_table        = $this->table;
        $participants_table = $wpdb->prefix . 'careplan_participants';

        return $wpdb->get_results(
            "
            SELECT 
                p.*,
                CONCAT(pt.first_name, ' ', pt.last_name) AS participant_name
            FROM {$plans_table} p
            LEFT JOIN {$participants_table} pt 
                ON p.participant_id = pt.id
            ORDER BY p.id DESC
            "
        );
    }
}
