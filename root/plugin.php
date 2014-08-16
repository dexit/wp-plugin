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

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */
{% if (autoloader) { %}
/**
 * Autoloads files with classes when needed
 * @since  0.1.0
 * @param  string $class_name Name of the class being requested
 */
function {%= prefix %}_autoload_classes( $class_name ) {
	if ( class_exists( $class_name, false ) || false === stripos( $class_name, '{%= class_name %}_' ) ) {
		return;
	}

	{%= class_name %}::include_file( $class_name );
}
spl_autoload_register( '{%= prefix %}_autoload_classes' );
{% } %}
/**
 * Main initiation class
 */
class {%= class_name %} {

	const VERSION = '0.1.0';
	{% if (!autoloader) { %}
	protected static $url  = '';
	protected static $path = '';
	{% } %}
	/**
	 * Sets up our plugin
	 * @since  0.1.0
	 */
	public function __construct() {
		// Useful variables
		self::$url  = trailingslashit( plugin_dir_url( __FILE__ ) );
		self::$path = trailingslashit( dirname( __FILE__ ) );
	}

	public function hooks() {

		register_activation_hook( __FILE__, array( $this, '_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, '_deactivate' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_hooks' ) );
	}

	/**
	 * Activate the plugin
	 */
	function _activate() {
		// Make sure any rewrite functionality has been loaded
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 */
	function _deactivate() {

	}

	/**
	 * Init hooks
	 * @since  0.1.0
	 * @return null
	 */
	public function init() {
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
	}{% if (autoloader) { %}

	/**
	 * Include a file from the includes directory
	 * @since  0.1.0
	 * @param  string $filename Name of the file to be included
	 */
	public static function include_file( $filename ) {
		$filename = strtolower( str_ireplace( '{%= class_name %}_', '', $filename ) );
		$file = self::dir( 'inc/'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
	}

	/**
	 * This plugin's directory
	 * @since  0.1.0
	 * @param  string $path (optional) appended path
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 * @since  0.1.0
	 * @param  string $path (optional) appended path
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}{% } %}

	/**
	 * Magic getter for our object.
	 *
	 * @param string $field
	 *
	 * @throws Exception Throws an exception if the field is invalid.
	 *
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'url':
			case 'path':
				return self::$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

}

// init our class
${%= class_name %} = new {%= class_name %}();
${%= class_name %}->hooks();

