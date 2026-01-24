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
    public function create_goal($data) {
        $defaults = [
            'plan_id'          => 0,
            'goal_title'       => '',
            'goal_description' => '',
            'priority'         => 0,
            'status'           => 'open',
        ];

        $data = wp_parse_args($data, $defaults);

        // Sanitize
        $data['plan_id']          = absint($data['plan_id']);
        $data['goal_title']       = sanitize_text_field($data['goal_title']);
        $data['goal_description'] = sanitize_textarea_field($data['goal_description']);
        $data['priority']         = intval($data['priority']);
        $data['status']           = sanitize_text_field($data['status']);

        // Format array matches the fields order
        $format = ['%d', '%s', '%s', '%d', '%s'];

        return $this->insert($data, $format);
    }

    /**
     * Update an existing goal
     *
     * @param int $id
     * @param array $data
     * @return int|false Rows affected or false
     */
    public function update_goal($id, $data) {
        $id = absint($id);

        if (isset($data['plan_id'])) $data['plan_id'] = absint($data['plan_id']);
        if (isset($data['goal_title'])) $data['goal_title'] = sanitize_text_field($data['goal_title']);
        if (isset($data['goal_description'])) $data['goal_description'] = sanitize_textarea_field($data['goal_description']);
        if (isset($data['priority'])) $data['priority'] = intval($data['priority']);
        if (isset($data['status'])) $data['status'] = sanitize_text_field($data['status']);

        $format = ['%d', '%s', '%s', '%d', '%s'];
        $where  = ['id' => $id];
        $where_format = ['%d'];

        return $this->update($data, $where, $format, $where_format);
    }

    /**
     * Delete goal by ID
     *
     * @param int $id
     * @return int|false Rows affected or false
     */
    public function delete_goal($id) {
        $id = absint($id);
        return $this->delete(['id' => $id], ['%d']);
    }

    /**
     * Get goal by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_goal($id) {
        $id = absint($id);
        $result = $this->get(['id' => $id]);
        return $result ?: null;
    }

    /**
     * Get all goals
     *
     * @param int|null $plan_id Optional plan ID filter
     * @return array Array of goal objects
     */
    public function get_all_goals($plan_id = null) {
        if ($plan_id) {
            return $this->get_all(['plan_id' => absint($plan_id)]) ?: [];
        }
        return $this->get_all() ?: [];
    }
}
