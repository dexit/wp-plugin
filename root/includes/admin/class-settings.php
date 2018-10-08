<?php

namespace Pluginever\{%= class_name %}\Admin;
class Settings {
    private $settings_api;

    function __construct() {
        $this->settings_api = new Ever_Settings_API();
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    function admin_init() {
        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );
        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_submenu_page( 'tools', 'Settings', 'Settings', 'manage_options', '{%= js_safe_name %}-settings', array(
            $this,
            'settings_page'
            ) );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => '{%= function_prefix %}_settings',
                'title' => __( 'Settings', '{%= text_domain %}' )
            ),
        );
        return apply_filters( '{%= function_prefix %}_settings_sections', $sections );
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            '{%= function_prefix %}_settings' => array(
                array(
                    'name'        => 'text',
                    'label'       => __( 'Text Field', '{%= text_domain %}' ),
                    'desc'        => __( 'Text Field Desc', '{%= text_domain %}' ),
                    'placeholder' => __( 'Place Holder', '{%= text_domain %}' ),
                    'type'        => 'text',
                ),
            )
            );
        return apply_filters( '{%= function_prefix %}_settings_fields', $settings_fields );
    }
    function settings_page() {
        ?>
        <?php
        echo '<div class="wrap">';
        echo sprintf( "<h2>%s</h2>", __( '{%= title %} Settings', '{%= text_domain %}' ) );
        $this->settings_api->show_settings();
        echo '</div>';
    }
    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages         = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ( $pages as $page ) {
                $pages_options[ $page->ID ] = $page->post_title;
            }
        }
        return $pages_options;
    }
}
