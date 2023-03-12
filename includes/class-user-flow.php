<?php
/**
 * User flow setup
 *
 * @package user-flow
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 *
 * @class User_Flow
 */
class User_Flow {

    /**
	 * User Flow version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';

    /**
     * User_Flow Constructor
     */
    public function __construct() {
        
        $this->define_constants();
        $this->includes();
        $this->init_hooks();

    }

    /**
	 * Define User flow Constants.
	 */
	private function define_constants() {

        if ( ! defined( 'USER_FLOW_VERSION' ) ) {
            define( 'USER_FLOW_VERSION', $this->version );
        }

        if ( ! defined( 'USER_FLOW_TEXT_DOMAIN' ) ) {
            define( 'USER_FLOW_TEXT_DOMAIN', 'user-flow' );
        }

        if ( ! defined( 'USER_FLOW_PLUGIN_PATH' ) ) {
            define( 'USER_FLOW_PLUGIN_PATH', plugin_dir_path( USER_FLOW_PLUGIN_FILE ) );
        }

        if ( ! defined( 'USER_FLOW_PLUGIN_URL' ) ) {
            define( 'USER_FLOW_PLUGIN_URL', plugin_dir_url( USER_FLOW_PLUGIN_FILE ) );
        }

    }

    /**
	 * Include required core files.
	 */
	public function includes() {

        include_once USER_FLOW_PLUGIN_PATH . 'includes/global-functions.php';
        include_once USER_FLOW_PLUGIN_PATH . 'includes/class-activated.php';
        include_once USER_FLOW_PLUGIN_PATH . 'includes/class-shortcodes.php';
        include_once USER_FLOW_PLUGIN_PATH . 'includes/class-redirection.php';

    }

    /**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {

        register_activation_hook( USER_FLOW_PLUGIN_FILE, array( 'User_Flow_Activate', 'plugin_activated' ) );
		add_action( 'init', array( 'User_Flow_Shortcodes', 'init' ) );
        add_action( 'login_form_login', array( 'User_Flow_Redirection', 'redirect_to_custom_login' ) );
        add_filter( 'authenticate', array( 'User_Flow_Redirection', 'redirect_if_errors' ), PHP_INT_MAX, 3 );
        add_action( 'wp_logout', array( 'User_Flow_Redirection', 'redirect_after_logout' ) );
        add_filter( 'login_redirect', array( 'User_Flow_Redirection', 'redirect_after_login' ), 10, 3 );

    }

}