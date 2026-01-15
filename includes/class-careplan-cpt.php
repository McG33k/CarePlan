<?php
defined( 'ABSPATH' ) || exit;

class CarePlan_CPT {

    public function __construct() {
        add_action( 'init', [ $this, 'register_participant_cpt' ] );
    }

    public function register_participant_cpt() {

        $labels = [
            'name'               => __( 'Participants', 'careplan' ),
            'singular_name'      => __( 'Participant', 'careplan' ),
            'add_new_item'       => __( 'Add New Participant', 'careplan' ),
            'edit_item'          => __( 'Edit Participant', 'careplan' ),
        ];

        $args = [
            'labels'        => $labels,
            'public'        => false,
            'show_ui'       => true,
            'show_in_menu'  => false, // Shown under CarePlan menu
            'supports'      => [ 'title' ],
            'menu_icon'     => 'dashicons-universal-access',
        ];

        register_post_type( 'careplan_participant', $args );
    }
}
