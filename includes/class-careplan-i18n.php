<?php
defined( 'ABSPATH' ) || exit;

class CarePlan_i18n {

    public static function load_textdomain() {
        load_plugin_textdomain(
            'careplan',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/../languages'
        );
    }
}
