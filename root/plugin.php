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

//check for dependency plugin
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}

/**
 * Main {%= class_name %} Class
 * 
 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @var string
	 */
    protected $min_wp = '4.0.0';
    
	/**
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $min_php = '5.6';

	/**
	 * admin notices
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
    protected $notices = array();

    /**
	 * The single instance of the class.
	 *
	 * @var {%= class_name %}
	 * @since 1.0.0
	 */
    protected static $instance = null;
    
    /**
	 * @since 1.0.0
	 *
	 * @var string
	 */
    public $plugin_name = '{%= title %}';

    /**
	 * {%= class_name %} constructor.
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activation_check' ) );

		add_action( 'admin_init', array( $this, 'check_environment' ) );
		add_action( 'admin_init', array( $this, 'add_plugin_notices' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );

		add_action( 'init', array( $this, 'localization_setup' ) );

		add_action( 'plugins_loaded', array( $this, 'instantiate' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

		// if the environment check fails, initialize the plugin
		if ( $this->is_environment_compatible() ) {
			require_once dirname( __FILE__ ) . '/includes/class-install.php';
			register_activation_hook( __FILE__, array( '{%= prefix %}_Install', 'activate' ) );
			register_deactivation_hook( __FILE__, array( '{%= prefix %}_Install', 'deactivate' ) );
			$this->init_plugin();
		}
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
	 * Universalizing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Universalizing instances of this class is forbidden.', '{%= text_domain %}' ), '1.0.0' );
	}


	/**
	 * Checks the environment on loading WordPress, just in case the environment changes after activation.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function check_environment() {

		if ( ! $this->is_environment_compatible() && is_plugin_active( plugin_basename( __FILE__ ) ) ) {

			$this->deactivate_plugin();

			$this->add_admin_notice( 'bad_environment', 'error', $this->plugin_name . ' has been deactivated. ' . $this->get_environment_message() );
		}
	}

	/**
	 * Adds notices for out-of-date WordPress and Dependent plugin versions.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function add_plugin_notices() {

		if ( ! $this->is_wp_compatible() ) {

			$this->add_admin_notice( 'update_wordpress', 'error', sprintf(
				'%s requires WordPress version %s or higher. Please %supdate WordPress &raquo;%s',
				'<strong>' . $this->plugin_name . '</strong>',
				$this->min_wp,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>'
			) );
		}

		if ( ! $this->is_wc_compatible() ) {
			$this->add_admin_notice( 'update_wc', 'error', sprintf(
				'%s requires WooCommerce version %s or higher. Please %supdate WooCommerce',
				'<strong>WooCommerce</strong>',
				$this->min_wc
			) );
		}
	}

	/**
	 * Determines if the server environment is compatible with this plugin.
	 *
	 * Override this method to add checks for more than just the PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function is_environment_compatible() {

		return version_compare( PHP_VERSION, $this->min_php, '>=' );
	}

	/**
	 * Determines if the WordPress compatible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function is_wp_compatible() {

		return version_compare( get_bloginfo( 'version' ), $this->min_wp, '>=' );
	}

	/**
	 * Determines if the WordPress compatible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function is_wc_compatible(){
		return defined( 'WC_VERSION' ) && version_compare( WC_VERSION, $this->min_wc, '>=' );
	}

	/**
	 * Determines if the required plugins are compatible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function plugins_compatible() {

		return $this->is_wp_compatible() && $this->is_wc_compatible();
	}

	/**
	 * Deactivates the plugin.
	 *
	 * @since 1.0.0
	 */
	protected function deactivate_plugin() {

        deactivate_plugins( plugin_basename( __FILE__ ) );
        
	}

	/**
	 * Adds an admin notice to be displayed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug the notice slug
	 * @param string $class the notice class
	 * @param string $message the notice message body
	 */
	public function add_admin_notice( $slug, $class, $message ) {

		$this->notices[ $slug ] = array(
			'class'   => $class,
			'message' => $message
		);
	}


	/**
	 * Displays any admin notices added
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {
		$notices = (array) array_merge( $this->notices, get_option( '{%= function_prefix %}_admin_notifications', [] ) );
		foreach ( $notices as $notice_key => $notice ) :

			?>
			<div class="notice <?php echo sanitize_html_class( $notice['class'] ); ?>">
				<p><?php echo wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) ); ?></p>
			</div>
			<?php
			update_option( '{%= function_prefix %}_admin_notifications', [] );
		endforeach;
	}

	/**
	 * Returns the message for display when the environment is incompatible with this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_environment_message() {

		return sprintf( 'The minimum PHP version required for this plugin is %1$s. You are running %2$s.', $this->min_php, PHP_VERSION );
	}

	/**
	 * Checks the server environment and other factors and deactivates plugins as necessary.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function activation_check() {

		if ( ! $this->is_environment_compatible() ) {

			$this->deactivate_plugin();

			wp_die( $this->plugin_name . ' could not be activated. ' . $this->get_environment_message() );
		}
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function localization_setup() {
		load_plugin_textdomain( '{%= function_prefix %}', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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

	/**
	 * Add notice to database
	 * since 1.0.0
	 *
	 * @param        $message
	 * @param string $type
	 *
	 * @return void
	 */
	public function add_notice( $message, $type = 'success' ) {
		$notices = get_option( '{%= function_prefix %}_admin_notifications', [] );
		if ( is_string( $message ) && is_string( $type ) && ! wp_list_filter( $notices, array( 'message' => $message ) ) ) {

			$notices[] = array(
				'message' => $message,
				'class'   => $type
			);

			update_option( '{%= function_prefix %}_admin_notifications', $notices );
		}
	}


	/**
	 * Initializes the plugin.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function init_plugin() {
		if ( $this->plugins_compatible() ) {
			$this->define_constants();
			$this->includes();
			do_action( '{%= function_prefix %}_loaded' );
		}
    }
    
      /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    public function is_request( $type ) {
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
     * Define EverProjects Constants.
     *
     * @since 1.0.0
     * @return void
     */
    private function define_constants() {
        //$upload_dir = wp_upload_dir( null, false );
        define( '{%= prefix %}_VERSION', $this->version );
        define( '{%= prefix %}_FILE', __FILE__ );
        define( '{%= prefix %}_PATH', dirname( {%= prefix %}_FILE ) );
        define( '{%= prefix %}_INCLUDES', {%= prefix %}_PATH . '/includes' );
        define( '{%= prefix %}_URL', plugins_url( '', {%= prefix %}_FILE ) );
        define( '{%= prefix %}_ASSETS_URL', {%= prefix %}_URL . '/assets' );
        define( '{%= prefix %}_VIEWS_DIR', {%= prefix %}_PATH . '/views' );
        define( '{%= prefix %}_TEMPLATES_DIR', {%= prefix %}_PATH . '/templates' );
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        //core includes
		include_once {%= prefix %}_INCLUDES . '/core-functions.php';
		include_once {%= prefix %}_INCLUDES . '/class-install.php';
		include_once {%= prefix %}_INCLUDES . '/class-post-types.php';

		//admin includes
		if ( $this->is_request( 'admin' ) ) {
            include_once {%= prefix %}_INCLUDES . '/class-upgrades.php';
		}

		//frontend includes
		if ( $this->is_request( 'frontend' ) ) {
			
		}

    }

    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', {%= prefix %}_FILE ) );
    }

    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( {%= prefix %}_FILE ) );
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return {%= prefix %}_TEMPLATES_DIR;
    }

    	/**
	 * Returns the plugin loader main instance.
	 *
	 * @since 1.0.0
	 * @return \{%= class_name %}
	 */
	public static function instance() {

		if ( null === self::$instance ) {

			self::$instance = new self();
		}

		return self::$instance;
    }
    
}

function {%= function_prefix %}(){
    return {%= class_name %}::instance();
}

//fire off the plugin
{%= function_prefix %}();
