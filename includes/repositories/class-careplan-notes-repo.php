<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';

/**
 * Repository for CarePlan Notes
 */
class CarePlan_Notes_Repo extends CarePlan_Repo {

    public function __construct() {
    global $wpdb;
    $this->wpdb  = $wpdb;
    $this->table = $this->wpdb->prefix . 'careplan_notes';
}


    /**
     * Create a new note
     *
     * @param array $data
     * @return int|false Insert ID on success, false on failure
     */
    public function create_note( $data ) {
        $defaults = [
            'participant_id' => 0,
            'note'           => '',
            'created_by'     => get_current_user_id(),
        ];

        $data = wp_parse_args( $data, $defaults );

        // Sanitize inputs
        $data['participant_id'] = absint( $data['participant_id'] );
        $data['note']           = sanitize_textarea_field( $data['note'] );
        $data['created_by']     = absint( $data['created_by'] );

        return $this->insert( $data, ['%d','%s','%d'] );
    }

    /**
     * Update a note by ID
     *
     * @param int $id
     * @param array $data
     * @return int|false Rows affected or false
     */
    public function update_note( $id, $data ) {
        $id = absint( $id );

        if ( isset( $data['participant_id'] ) ) {
            $data['participant_id'] = absint( $data['participant_id'] );
        }
        if ( isset( $data['note'] ) ) {
            $data['note'] = sanitize_textarea_field( $data['note'] );
        }
        if ( isset( $data['created_by'] ) ) {
            $data['created_by'] = absint( $data['created_by'] );
        }

        return $this->update( $data, ['id' => $id], ['%d','%s','%d'], ['%d'] );
    }

    /**
     * Delete note by ID
     *
     * @param int $id
     * @return int|false Rows affected or false
     */
    public function delete_note( $id ) {
        $id = absint( $id );
        return $this->delete( ['id' => $id], ['%d'] );
    }

    /**
     * Get a note by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_note( $id ) {
        $id = absint( $id );
        $result = $this->get( ['id' => $id] );
        return $result ?: null;
    }

    /**
     * Get all notes
     *
     * @param int|null $participant_id Optional filter by participant
     * @return array Array of note objects
     */
    public function get_all_notes( $participant_id = null ) {
        if ( $participant_id ) {
            $participant_id = absint( $participant_id );
            $results = $this->get_all( ['participant_id' => $participant_id] );
        } else {
            $results = $this->get_all();
        }
        return $results ?: [];
    }
}
