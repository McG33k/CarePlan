<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';

class CarePlan_Plans_Repo extends CarePlan_Repo {

    public function __construct() {
        parent::__construct();
        $this->table = $this->wpdb->prefix . 'careplan_plans';
    }

    public function create_plan($data) {
        $defaults = [
            'participant_id' => 0,
            'plan_start' => null,
            'plan_end' => null,
            'total_budget' => 0,
            'status' => 'active',
        ];
        $data = wp_parse_args($data, $defaults);
        return $this->insert($data, ['%d','%s','%s','%f','%s']);
    }

    public function update_plan($id, $data) {
        return $this->update($data, ['id' => $id], ['%d','%s','%s','%f','%s'], ['%d']);
    }

    public function delete_plan($id) {
        return $this->delete(['id' => $id], ['%d']);
    }

    public function get_plan($id) {
        return $this->get(['id' => $id]);
    }

    public function get_all_plans($participant_id = null) {
        if ($participant_id) {
            return $this->get_all(['participant_id' => $participant_id]);
        }
        return $this->get_all();
    }
}
