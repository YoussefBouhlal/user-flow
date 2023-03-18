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

/**
 * Get error messages from error codes
 */
function user_flow_get_error_message( $error_code ) {

    $message = __( 'Please try again later.', USER_FLOW_TEXT_DOMAIN );

    switch ( $error_code ) {
        case 'empty_username':
            $message = __( 'You need to enter an email to login.', USER_FLOW_TEXT_DOMAIN );
        break;
        case 'empty_password':
            $message = __( 'You need to enter a password to login.', USER_FLOW_TEXT_DOMAIN );
        break;
        case 'invalid_username':
            $message = __( 'Your email is incorrect.', USER_FLOW_TEXT_DOMAIN );
        break;
        case 'incorrect_password':
            $message = sprintf( __( 'Your passsword is incorrect.', USER_FLOW_TEXT_DOMAIN ) );
        break;
        case 'email':
            $message = __( 'The email address you entered is not valid.', USER_FLOW_TEXT_DOMAIN );
        break;
        case 'email_exists':
            $message = __( 'An account exists with this email address.', USER_FLOW_TEXT_DOMAIN );
        break;
        case 'closed':
            $message = __( 'Registering new users is currently not allowed.', USER_FLOW_TEXT_DOMAIN );
        break;
    }

    return $message;
}