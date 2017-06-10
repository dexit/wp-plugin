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
 * Text Domain: {%= prefix %}
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
 */
include( dirname( __FILE__ ) . '/lib/requirements-check.php' );

class {%= class_name %} {

	public $version = '1.0.0';

	public $dependency_plugins = [];

	
	/**
	 * Sets up our plugin
	 * @since  0.1.0
	 */
	public function __construct() {

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		add_action( 'admin_init', array( $this, 'admin_hooks' ) );
		add_action( 'init', [ $this, 'localization_setup' ] );
		$this->define_constants();
		$this->includes();
		add_action('wp_enqueue_scripts', [$this, 'load_assets']);
	}

	/**
	 * Activate the plugin
	 */
	function activate() {
		// Make sure any rewrite functionality has been loaded
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 */
	function deactivate() {

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
		load_textdomain( '{%= prefix %}', WP_LANG_DIR . '/{%= prefix %}/{%= prefix %}-' . $locale . '.mo' );
		load_plugin_textdomain( '{%= prefix %}', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}



	/**
	 * Hooks for the Admin
	 * @since  0.1.0
	 * @return null
	 */
	public function admin_hooks() {

	}

	/**
	 * Include a file from the includes directory
	 * @since  0.1.0
	 * @param  string $filename Name of the file to be included
	 */
	public function includes( ) {
		require {%= constant_prefix %}_INCLUDES .'/functions.php';
	}


	/**
	 * Define Add-on constants
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



	/**
	 * Display an error message if WP ERP is not active
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_notice($type='error', $message) {
		printf(
			'%s'. __( $message, '{%= prefix %}' ) . '%s',
			'<div class="message '.$type.'"><p>',
			'</p></div>'
		);
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
