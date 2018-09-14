<?php
/**
 * Plugin Name: {%= title %}
 * Plugin URI:  {%= homepage %}
 * Description: {%= description %}
 * Version:     1.0.0
 * Author:      {%= author_name %}
 * Author URI:  {%= author_url %}
 * Donate link: {%= homepage %}
 * License:     GPLv2+
 * Text Domain: {%= text_domain %}
 * Domain Path: /i18n/languages/
 */

/**
 * Copyright (c) {%= grunt.template.today('yyyy') %} {%= author_name %} (email : {%= author_email %})
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Main initiation class
 *
 * @since 1.0.0
 */

/**
 * Main {%= class_name %} Class.
 *
 * @class {%= class_name %}
 */
final class {%= class_name %} {
    /**
     * {%= class_name %} version.
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Minimum PHP version required
     *
     * @var string
     */
    private $min_php = '5.6.0';

    /**
     * The single instance of the class.
     *
     * @var {%= class_name %}
     * @since 1.0.0
     */
    protected static $instance = null;


    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Main {%= class_name %} Instance.
     *
     * Ensures only one instance of {%= class_name %} is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return {%= class_name %} - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->setup();
        }

        return self::$instance;
    }

    /**
     * Cloning is forbidden.
     *
     * @since 1.0
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', '{%= text_domain %}' ), '1.0.0' );
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', '{%= text_domain %}' ), '2.1' );
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * EverProjects Constructor.
     */
    public function setup() {
        $this->check_environment();
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        $this->plugin_init();
        do_action( '{%= function_prefix %}_loaded' );
    }

    /**
     * Ensure theme and server variable compatibility
     */
    public function check_environment() {
        if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );

            wp_die( "Unsupported PHP version Min required PHP Version:{$this->min_php}" );
        }
    }

    /**
     * Define EverProjects Constants.
     *
     * @since 1.0.0
     * @return void
     */
    private function define_constants() {
        //$upload_dir = wp_upload_dir( null, false );
        define( '{%= constant_prefix %}_VERSION', $this->version );
        define( '{%= constant_prefix %}_FILE', __FILE__ );
        define( '{%= constant_prefix %}_PATH', dirname( {%= constant_prefix %}_FILE ) );
        define( '{%= constant_prefix %}_INCLUDES', {%= constant_prefix %}_PATH . '/includes' );
        define( '{%= constant_prefix %}_URL', plugins_url( '', {%= constant_prefix %}_FILE ) );
        define( '{%= constant_prefix %}_ASSETS_URL', {%= constant_prefix %}_URL . '/assets' );
        define( '{%= constant_prefix %}_TEMPLATES_DIR', {%= constant_prefix %}_PATH . '/templates' );
    }


    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined( 'DOING_AJAX' );
            case 'cron':
                return defined( 'DOING_CRON' );
            case 'frontend':
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
        }
    }


    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        //core includes
		include_once {%= constant_prefix %}_INCLUDES . '/core-functions.php';
		include_once {%= constant_prefix %}_INCLUDES . '/class-install.php';
		include_once {%= constant_prefix %}_INCLUDES . '/class-post-types.php';

		//admin includes
		if ( $this->is_request( 'admin' ) ) {
			include_once {%= constant_prefix %}_INCLUDES . '/admin/class-admin.php';
		}

		//frontend includes
		if ( $this->is_request( 'frontend' ) ) {
			include_once {%= constant_prefix %}_INCLUDES . '/class-frontend.php';
		}

    }

    /**
     * Hook into actions and filters.
     *
     * @since 2.3
     */
    private function init_hooks() {
        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );

        //add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
    }

    /**
     * Initialize plugin for localization
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( '{%= text_domain %}', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Plugin action links
     *
     * @param  array $links
     *
     * @return array
     */
    public function plugin_action_links( $links ) {
        //$links[] = '<a href="' . admin_url( 'admin.php?page=' ) . '">' . __( 'Settings', '' ) . '</a>';
        return $links;
    }

    public function plugin_init() {
        new \Pluginever\{%= class_name %}\PostTypes();
    }

    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', {%= constant_prefix %}_FILE ) );
    }

    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( {%= constant_prefix %}_FILE ) );
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return {%= constant_prefix %}_TEMPLATES_DIR;
    }

}

function {%= function_prefix %}(){
    return {%= class_name %}::instance();
}

//fire off the plugin
{%= function_prefix %}();
