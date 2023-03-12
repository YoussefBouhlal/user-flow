<?php
/**
 * Shortcodes
 *
 * @package user-flow/includes
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * User Flow Shortcodes
 */
class User_Flow_Shortcodes {

    /**
     * Init the shortcodes class
     */
    public static function init() {

		add_shortcode( 'user-flow-login-form', __CLASS__ . '::render_login_form' );

    }

    /**
     * Render the [user-flow-login-form] shortcode
     */
    public static function render_login_form( $atts, $content = null ) {

        $atts = shortcode_atts( array(
            'show_title'    => false
        ), $atts );

        $atts['show_title'] = sanitize_text_field( $atts['show_title'] );
        $atts['errors']     = self::get_errors();
        $atts['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;
        $atts['redirect']   = '';
        
        if ( isset( $_REQUEST['redirect_to'] ) ) {
            $atts['redirect'] = wp_validate_redirect( sanitize_url( $_REQUEST['redirect_to'] ), $atts['redirect'] );
        }
        
        if ( is_user_logged_in() ) {
            
            $content = __( 'You are already signed in.', USER_FLOW_TEXT_DOMAIN );
            
        } else {

            $content = user_flow_get_template( 'login_form', $atts );
        }

        return $content;
    }

    /**
     * Get errors if exist
     */
    private static function get_errors() {

        $errors = array();

        if ( isset( $_REQUEST['login'] ) ) {
            $error_codes = explode( ',', $_REQUEST['login'] );

            foreach ( $error_codes as $code ) {
                $errors[] = self::get_error_message( $code );
            }
        }

        return $errors;
    }

    /**
     * Get error messages from error codes
     */
    private static function get_error_message( $error_code ) {

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
        }

        return $message;
    }


}