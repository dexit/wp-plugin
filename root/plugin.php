<?php
/**
 * Plugin Name: {%= title %}
 * Plugin URI:  {%= homepage %}
 * Description: {%= description %}
 * Version:     0.1.0
 * Author:      {%= author_name %}
 * Author URI:  {%= author_url %}
 * Donate link: {%= homepage %}
 * License:     GPLv2+
 * Text Domain: {%= text_domain %}
 * Domain Path: /languages
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
class {%= class_name %} {

    /**
     * Add-on Version
     *
     * @since 1.0.0
     * @var  string
     */
	public $version = '1.0.0';

	/**
	 * Initializes the class
	 *
	 * Checks for an existing instance
	 * and if it does't find one, creates it.
	 *
	 * @since 1.0.0
	 *
	 * @return object Class instance
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Constructor for the class
	 *
	 * Sets up all the appropriate hooks and actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		// Localize our plugin
		add_action( 'init', [ $this, 'localization_setup' ] );

		// on activate plugin register hook
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		// on deactivate plugin register hook
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Initialize the action hooks
		$this->init_actions();

		// instantiate classes
		$this->instantiate();

		// Loaded action
		do_action( '{%= prefix %}' );
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function localization_setup() {
		$locale = apply_filters( 'plugin_locale', get_locale(), '{%= prefix %}' );
		load_textdomain( '{%= text_domain %}', WP_LANG_DIR . '/{%= text_domain %}/{%= text_domain %}-' . $locale . '.mo' );
		load_plugin_textdomain( '{%= text_domain %}', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Executes during plugin activation
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function activate() {
		flush_rewrite_rules();
	}

	/**
	 * Executes during plugin deactivation
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function deactivate() {

	}

	/**
	 * Define constants
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function define_constants() {
		define( '{%= constant_prefix %}_VERSION', $this->version );
		define( '{%= constant_prefix %}_FILE', __FILE__ );
		define( '{%= constant_prefix %}_PATH', dirname( {%= constant_prefix %}_FILE ) );
		define( '{%= constant_prefix %}_INCLUDES', {%= constant_prefix %}_PATH . '/includes' );
		define( '{%= constant_prefix %}_URL', plugins_url( '', {%= constant_prefix %}_FILE ) );
		define( '{%= constant_prefix %}_ASSETS', {%= constant_prefix %}_URL . '/assets' );
		define( '{%= constant_prefix %}_VIEWS', {%= constant_prefix %}_PATH . '/views' );
		define( '{%= constant_prefix %}_TEMPLATES_DIR', {%= constant_prefix %}_PATH . '/templates' );
	}

	/**
	 * Include required files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function includes( ) {
		require {%= constant_prefix %}_INCLUDES .'/functions.php';
	}

	/**
	 * Instantiate classes
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function instantiate() {

	}
	
	/**
	 * Add all the assets required by the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_assets(){
		wp_register_style('{%= wpfilename %}', {%= constant_prefix %}_ASSETS.'/css/{%= wpfilename %}.css', [], date('i'));
		wp_register_script('{%= wpfilename %}', {%= constant_prefix %}_ASSETS.'/js/{%= wpfilename %}.js', ['jquery'], date('i'), true);
		wp_localize_script('{%= wpfilename %}', 'jsobject', ['ajaxurl' => admin_url( 'admin-ajax.php' )]);
		wp_enqueue_style('{%= wpfilename %}');
		wp_enqueue_script('{%= wpfilename %}');
	}

	public static function log($message){
		if( WP_DEBUG !== true ) return;
		if (is_array($message) || is_object($message)) {
			$message = print_r($message, true);
		}
		$debug_file = WP_CONTENT_DIR . '/custom-debug.log';
		if (!file_exists($debug_file)) {
			@touch($debug_file);
		}
		return error_log(date("Y-m-d\tH:i:s") . "\t\t" . strip_tags($message) . "\n", 3, $debug_file);
	}

}

// init our class
$GLOBALS['{%= class_name %}'] = new {%= class_name %}();

/**
 * Grab the ${%= class_name %} object and return it
 */
function {%= prefix %}() {
	global ${%= class_name %};
	return ${%= class_name %};
}
