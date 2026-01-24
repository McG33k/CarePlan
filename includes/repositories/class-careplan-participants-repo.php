<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';

/**
 * Repository for CarePlan Participants
 */
class CarePlan_Participants_Repo extends CarePlan_Repo {

    public function __construct() {
        global $wpdb;
        $this->wpdb  = $wpdb;
        $this->table = $this->wpdb->prefix . 'careplan_participants';
    }

    /**
     * Create a new participant
     *
     * @param array $data
     * @return int|false Insert ID on success, false on failure
     */
    public function create_participant( $data ) {

        $defaults = [
            'first_name'    => '',
            'last_name'     => '',
            'ndis_number'   => '',
            'date_of_birth' => null,
        ];

        $data = wp_parse_args( $data, $defaults );

        // Sanitize
        $data['first_name']    = sanitize_text_field( $data['first_name'] );
        $data['last_name']     = sanitize_text_field( $data['last_name'] );
        $data['ndis_number']   = sanitize_text_field( $data['ndis_number'] );
        $data['date_of_birth'] = ! empty( $data['date_of_birth'] )
            ? sanitize_text_field( $data['date_of_birth'] )
            : null;

        return $this->insert(
            $data,
            ['%s', '%s', '%s', '%s']
        );
    }

    /**
     * Update participant by ID
     *
     * @param int $id
     * @param array $data
     * @return int|false Number of rows affected or false
     */
    public function update_participant( $id, $data ) {
        $id = absint( $id );

        // Sanitize if set
        if ( isset( $data['first_name'] ) ) {
            $data['first_name'] = sanitize_text_field( $data['first_name'] );
        }
        if ( isset( $data['last_name'] ) ) {
            $data['last_name'] = sanitize_text_field( $data['last_name'] );
        }
        if ( isset( $data['ndis_number'] ) ) {
            $data['ndis_number'] = sanitize_text_field( $data['ndis_number'] );
        }
        if ( isset( $data['date_of_birth'] ) ) {
            $data['date_of_birth'] = sanitize_text_field( $data['date_of_birth'] );
        }
        if ( isset( $data['user_id'] ) ) {
            $data['user_id'] = absint( $data['user_id'] );
        }

        // ✅ Dynamically build correct formats (prevents string → 0 bugs)
        $formats = [];
        foreach ( $data as $value ) {
            $formats[] = is_int( $value ) ? '%d' : '%s';
        }

        return $this->update(
            $data,
            ['id' => $id],
            $formats,
            ['%d']
        );
    }

    /**
     * Delete participant by ID
     *
     * @param int $id
     * @return int|false Rows affected or false
     */
    public function delete_participant( $id ) {
        $id = absint( $id );
        return $this->delete( ['id' => $id], ['%d'] );
    }

    /**
     * Get participant by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_participant( $id ) {
        $id = absint( $id );
        $result = $this->get( ['id' => $id] );
        return $result ?: null;
    }

    /**
     * Get all participants
     *
     * @return array Array of participant objects
     */
    public function get_all_participants() {
        $results = $this->get_all();
        return $results ?: [];
    }
}
