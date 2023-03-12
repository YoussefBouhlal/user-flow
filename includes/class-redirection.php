<?php
/**
 * Redirections
 *
 * @package user-flow/includes
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * User Flow Redirection
 */
class User_Flow_Redirection {

    /**
     * Redirect from from standard login to our custom login page
     */
    public static function redirect_to_custom_login() {

        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {

            $redirect_to = isset( $_REQUEST['redirect_to'] ) ? sanitize_url( $_REQUEST['redirect_to'] ) : null;
        
            if ( is_user_logged_in() ) {
                self::redirect_logged_in_user( $redirect_to );
                exit;
            }

            $login_url = home_url( 'member-login' );
            if ( ! empty( $redirect_to ) ) {
                $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
            }

            wp_redirect( $login_url );
            exit;
        }
    }

    /**
     * Redirect if there is an error
     */
    public static function redirect_if_errors( $user, $username, $password ) {

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

            if ( is_wp_error( $user ) ) {

                $error_codes    = join( ',', $user->get_error_codes() );
                $login_url      = home_url( 'member-login' );
                $login_url      = add_query_arg( 'login', $error_codes, $login_url );

                wp_redirect( $login_url );
                exit;
            }
        }

        return $user;
    }

    /**
     * Redirect after logout
     */
    public static function redirect_after_logout() {

        $redirect_url = home_url( 'member-login?logged_out=true' );
        wp_safe_redirect( $redirect_url );
        exit;
    }

    /**
     * Redirect after login
     */
    public static function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

        $redirect_url = home_url();

        if ( ! isset( $user->ID ) ) {
            return $redirect_url;
        }

        if ( user_can( $user, 'manage_options' ) ) {

            if ( $requested_redirect_to == '' ) {
                $redirect_url = admin_url();
            } else {
                $redirect_url = $requested_redirect_to;
            }

        } else {

            $redirect_url = home_url( 'member-account' );
        }
        
        return wp_validate_redirect( $redirect_url, home_url() );
    }

    /**
     * Redirect the logged in users
     */
    private static function redirect_logged_in_user( $redirect_to = null ) {

        $user = wp_get_current_user();

        if ( user_can( $user, 'manage_options' ) ) {

            if ( $redirect_to ) {
                wp_safe_redirect( $redirect_to );
            } else {
                wp_redirect( admin_url() );
            }

        } else {
            wp_redirect( home_url( 'member-account' ) );
        }
    }

}