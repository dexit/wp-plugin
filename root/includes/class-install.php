<?php
namespace Pluginever\{%= namespace %};

class Install{

	/**
	 * Constructor for the class {%= name %}
	 *
	 * Sets up all the appropriate hooks and actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		register_activation_hook( {%= constant_prefix %}_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( {%= constant_prefix %}_FILE, array( $this, 'deactivate' ) );

    }

    /**
	 * Executes during plugin activation
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function activate() {


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



}