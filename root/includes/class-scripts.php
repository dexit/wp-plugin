<?php
namespace Pluginever\{%= namespace %};

class Scripts{

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
		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_assets') );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets') );
    }

   	/**
	 * Add all the assets of the public side
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_public_assets(){
		$suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';
		wp_register_style('{%= wpfilename %}', {%= constant_prefix %}_ASSETS."/css/{%= wpfilename %}-public{$suffix}.css", [], {%= constant_prefix %}_VERSION);
		wp_register_script('{%= wpfilename %}', {%= constant_prefix %}_ASSETS."/js/{%= wpfilename %}-public{$suffix}.js", ['jquery'], {%= constant_prefix %}_VERSION, true);
		wp_localize_script('{%= wpfilename %}', '{%= js_object %}', ['ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => '{%= wpfilename %}']);
		wp_enqueue_style('{%= wpfilename %}');
		wp_enqueue_script('{%= wpfilename %}');
	}

	 /**
	 * Add all the assets required by the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_admin_assets(){
		$suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';
		wp_register_style('{%= wpfilename %}', {%= constant_prefix %}_ASSETS."/css/{%= wpfilename %}-admin{$suffix}.css", [], {%= constant_prefix %}_VERSION);
		wp_register_script('{%= wpfilename %}', {%= constant_prefix %}_ASSETS."/js/{%= wpfilename %}-admin{$suffix}.js", ['jquery'], {%= constant_prefix %}_VERSION, true);
		wp_localize_script('{%= wpfilename %}', '{%= js_object %}', ['ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => '{%= wpfilename %}']);
		wp_enqueue_style('{%= wpfilename %}');
		wp_enqueue_script('{%= wpfilename %}');
	}



}