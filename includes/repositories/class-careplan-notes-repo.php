<?php
defined('ABSPATH') || exit;

require_once CAREPLAN_PATH . 'includes/repositories/class-careplan-repo.php';

class CarePlan_Notes_Repo extends CarePlan_Repo {

    public function __construct() {
        parent::__construct();
        $this->table = $this->wpdb->prefix . 'careplan_notes';
    }

    public function create_note($data) {
        $defaults = [
            'participant_id' => 0,
            'note' => '',
            'created_by' => get_current_user_id(),
        ];
        $data = wp_parse_args($data, $defaults);
        return $this->insert($data, ['%d','%s','%d']);
    }

    public function update_note($id, $data) {
        return $this->update($data, ['id' => $id], ['%d','%s','%d'], ['%d']);
    }

    public function delete_note($id) {
        return $this->delete(['id' => $id], ['%d']);
    }

    public function get_note($id) {
        return $this->get(['id' => $id]);
    }

    public function get_all_notes($participant_id = null) {
        if ($participant_id) {
            return $this->get_all(['participant_id' => $participant_id]);
        }
        return $this->get_all();
    }
}
