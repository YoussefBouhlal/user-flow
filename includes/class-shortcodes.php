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
        add_shortcode( 'user-flow-register-form', __CLASS__ . '::render_register_form' );

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
        $atts['registered'] = isset( $_REQUEST['registered'] );
        
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
     * Render the [user-flow-register-form] shortcode
     */
    public static function render_register_form( $atts, $content = null ) {

        $atts = shortcode_atts( array(
            'show_title'    => false
        ), $atts );

        $atts['show_title'] = sanitize_text_field( $atts['show_title'] );
        $atts['errors']     = self::get_errors();

        if ( is_user_logged_in() ) {

            $content = __( 'You are already signed in.', USER_FLOW_TEXT_DOMAIN );

        } elseif ( ! get_option( 'users_can_register' ) ) {

            $content = __( 'Registering new users is currently not allowed.', USER_FLOW_TEXT_DOMAIN );

        } else {

            $content = user_flow_get_template( 'register_form', $atts );
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
                $errors[] = user_flow_get_error_message( $code );
            }
        }

        if ( isset( $_REQUEST['register-errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['register-errors'] );
            
            foreach ( $error_codes as $code ) {
                $attributes['errors'] []= user_flow_get_error_message( $code );
            }
        }

        return $errors;
    }

}