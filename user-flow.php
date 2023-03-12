<?php
/*
Plugin Name: User Flow
Description: Replace the default WordPress login flow
Author: Youssef bouhlal
Author URI: https://github.com/YoussefBouhlal
Version: 1.0.0
Text Domain: user-flow
*/

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'USER_FLOW_PLUGIN_FILE' ) ) {
	define( 'USER_FLOW_PLUGIN_FILE', __FILE__ );
}

include_once dirname( USER_FLOW_PLUGIN_FILE ) . '/includes/class-user-flow.php';
new User_Flow();