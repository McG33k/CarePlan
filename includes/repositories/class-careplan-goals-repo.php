<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';

class CarePlan_Goals_Repo extends CarePlan_Repo {

    public function __construct() {
        parent::__construct();
        $this->table = $this->wpdb->prefix . 'careplan_goals';
    }

    public function create_goal($data) {
        $defaults = [
            'participant_id' => 0,
            'goal_title' => '',
            'goal_description' => '',
            'priority' => 0,
            'status' => 'open',
        ];
        $data = wp_parse_args($data, $defaults);
        return $this->insert($data, ['%d','%s','%s','%d','%s']);
    }

    public function update_goal($id, $data) {
        return $this->update($data, ['id' => $id], ['%d','%s','%s','%d','%s'], ['%d']);
    }

    public function delete_goal($id) {
        return $this->delete(['id' => $id], ['%d']);
    }

    public function get_goal($id) {
        return $this->get(['id' => $id]);
    }

    public function get_all_goals($participant_id = null) {
        if ($participant_id) {
            return $this->get_all(['participant_id' => $participant_id]);
        }
        return $this->get_all();
    }
}
