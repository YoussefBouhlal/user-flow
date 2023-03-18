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
     * Redirect from standard login to our custom login page
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
     * Redirect from standard register to our custom register page
     */
    public static function redirect_to_custom_register() {

        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {

            if ( is_user_logged_in() ) {

                self::redirect_logged_in_user();

            } else {

                wp_redirect( home_url( 'member-register' ) );
            }

            exit;
        }
    }

    /**
     * Handle the register form submit
     */
    public static function submit_register_form() {

        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

            $redirect_url = home_url( 'member-register' );

            if ( ! get_option( 'users_can_register' ) ) {
                
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );

            } else {

                $email      = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
                $first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
                $last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
                $result     = self::register_user( $email, $first_name, $last_name );

                if ( is_wp_error( $result ) ) {

                    $errors         = join( ',', $result->get_error_codes() );
                    $redirect_url   = add_query_arg( 'register-errors', $errors, $redirect_url );

                } else {

                    $redirect_url   = home_url( 'member-login' );
                    $redirect_url   = add_query_arg( 'registered', $email, $redirect_url );
                }
            }

            wp_redirect( $redirect_url );
            exit;
        }

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

    /**
     * 
     */
    private static function register_user( $email, $first_name, $last_name ) {
        $errors = new WP_Error();

        if ( ! is_email( $email ) ) {

            $errors->add( 'email', user_flow_get_error_message( 'email' ) );
            return $errors;
        }

        if ( username_exists( $email ) || email_exists( $email ) ) {

            $errors->add( 'email_exists', user_flow_get_error_message( 'email_exists') );
            return $errors;
        }

        $password   = wp_generate_password( 12, false );
        $user_data  = array(
            'user_login'    => $email,
            'user_email'    => $email,
            'user_pass'     => $password,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'nickname'      => $first_name,
        );
        $user_id = wp_insert_user( $user_data );
        wp_new_user_notification( $user_id, $password );
        
        return $user_id;
    }

}