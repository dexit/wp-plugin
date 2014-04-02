<?php
/**
 * Plugin Name: {%= title %}
 * Plugin URI:  {%= homepage %}
 * Description: {%= description %}
 * Version:     0.1.0
 * Author:      {%= author_name %}
 * Author URI:  {%= author_url %}
 * Donate link: {%= donate_link %}
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

// Useful global constants
define( '{%= prefix_caps %}_URL', plugin_dir_url( __FILE__ ) );
define( '{%= prefix_caps %}_PATH', dirname( __FILE__ ) . '/' );


class {%= class_name %} {

	const VERSION = '0.1.0';

	/**
	 * Sets up our plugin
	 * @since  0.1.0
	 */
	public function __construct() {
	}

	public function hooks() {

		register_activation_hook( __FILE__, '_activate' );
		register_deactivation_hook( __FILE__, '_deactivate' );
		add_action( 'init', array( $this, 'hooks' )  );
		add_action( 'admin_init', array( $this, 'admin_hooks' )  );
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
	public function hooks() {
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

}

// init our class
${%= class_name %} = new {%= class_name %}();
${%= class_name %}->hooks();

