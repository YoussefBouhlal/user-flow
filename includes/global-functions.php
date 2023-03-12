<?php
/**
 * Global functions
 *
 * @package user-flow/includes
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get a template
 */
function user_flow_get_template( $template_name, $atts = array() ) {

    ob_start();

    require( USER_FLOW_PLUGIN_PATH . 'templates/' . $template_name . '.php');

    $template_content = ob_get_contents();
    ob_end_clean();

    return $template_content;
}