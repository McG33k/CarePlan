<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';

/**
 * Repository for CarePlan Notes
 *
 * Notes belong to a Goal (goal_id)
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
    public function create_note($data) {
        $defaults = [
            'goal_id'    => 0,
            'note'       => '',
            'created_by' => get_current_user_id(),
        ];

        $data = wp_parse_args($data, $defaults);

        // Sanitize
        $data['goal_id']    = absint($data['goal_id']);
        $data['note']       = sanitize_textarea_field($data['note']);
        $data['created_by'] = absint($data['created_by']);

        if (empty($data['goal_id']) || empty($data['note'])) {
            return false;
        }

        return $this->insert(
            [
                'goal_id'    => $data['goal_id'],
                'note'       => $data['note'],
                'created_by' => $data['created_by'],
            ],
            ['%d', '%s', '%d']
        );
    }

    /**
     * Update a note by ID
     *
     * @param int   $id
     * @param array $data
     * @return int|false Rows affected or false
     */
    public function update_note($id, $data) {
        $id = absint($id);

        if (isset($data['goal_id'])) {
            $data['goal_id'] = absint($data['goal_id']);
        }
        if (isset($data['note'])) {
            $data['note'] = sanitize_textarea_field($data['note']);
        }
        if (isset($data['created_by'])) {
            $data['created_by'] = absint($data['created_by']);
        }

        return $this->update(
            $data,
            ['id' => $id],
            ['%d', '%s', '%d'],
            ['%d']
        );
    }

    /**
     * Delete note by ID
     *
     * @param int $id
     * @return int|false Rows affected or false
     */
    public function delete_note($id) {
        return $this->delete(['id' => absint($id)], ['%d']);
    }

    /**
     * Get a note by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_note($id) {
        $result = $this->get(['id' => absint($id)]);
        return $result ?: null;
    }

    /**
     * Get all notes for a specific goal
     *
     * @param int $goal_id
     * @return array
     */
    public function get_notes_by_goal($goal_id) {
        $goal_id = absint($goal_id);
        if (!$goal_id) {
            return [];
        }

        return $this->get_all(['goal_id' => $goal_id]) ?: [];
    }

    /**
     * Get all notes (admin/debug use)
     *
     * @return array
     */
    public function get_all_notes() {
        return $this->get_all() ?: [];
    }
}
