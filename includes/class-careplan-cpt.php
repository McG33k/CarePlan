<?php
class CarePlan_CPT {

    public function __construct() {
        add_action('init', [$this, 'register_participants']);
    }

    public function register_participants() {
        register_post_type('careplan_participant', [
            'label' => 'Participants',
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-universal-access',
            'supports' => ['title'],
        ]);
    }
}
