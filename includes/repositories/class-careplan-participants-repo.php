<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';

class CarePlan_Participants_Repo extends CarePlan_Repo {

    public function __construct() {
        parent::__construct();
        $this->table = $this->wpdb->prefix . 'careplan_participants';
    }

    public function create_participant($data) {
        $defaults = [
            'user_id' => 0,
            'first_name' => '',
            'last_name' => '',
            'ndis_number' => '',
            'date_of_birth' => null,
        ];
        $data = wp_parse_args($data, $defaults);
        return $this->insert($data, ['%d','%s','%s','%s','%s']);
    }

    public function update_participant($id, $data) {
        return $this->update($data, ['id' => $id], ['%d','%s','%s','%s','%s'], ['%d']);
    }

    public function delete_participant($id) {
        return $this->delete(['id' => $id], ['%d']);
    }

    public function get_participant($id) {
        return $this->get(['id' => $id]);
    }

    public function get_all_participants() {
        return $this->get_all();
    }
}
