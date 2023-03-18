<?php
/**
 * Activation
 *
 * @package user-flow/includes
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * User Flow Activation
 */
class User_Flow_Activate {

    /**
     * Init the Activation class
     */
    public static function plugin_activated() {
        
        self::generate_pages();
        
    }

    /**
     * Generate required pages
     */
    private static function generate_pages() {

        $required_pages   = array(
            'member-login'      => array(
                'slug'      => 'member-login',
                'title'     => __( 'Sign In', USER_FLOW_TEXT_DOMAIN ),
                'content'   => '[user-flow-login-form]'
            ),
            'member-account'    => array(
                'slug'      => 'member-account',
                'title'     => __( 'Your Account', USER_FLOW_TEXT_DOMAIN ),
                'content'   => '[user-flow-account-info]'
            ),
            'member-register' => array(
                'slug'      => 'member-register',
                'title'     => __( 'Register', USER_FLOW_TEXT_DOMAIN ),
                'content'   => '[user-flow-register-form]'
            )
        );

        foreach ( $required_pages as $page ) {

            $page_slug  = $page['slug'] ?? '';
            $query      = new WP_Query( 'pagename=' . $page_slug );

            if ( ! $query->have_posts() && ! empty( $page_slug ) ) {

                wp_insert_post( array(
                    'post_content'   => sanitize_text_field( $page['content'] ?? '' ),
                    'post_name'      => sanitize_text_field( $page['slug'] ?? '' ),
                    'post_title'     => sanitize_text_field( $page['title'] ?? '' ),
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'ping_status'    => 'closed',
                    'comment_status' => 'closed',
                ));

            }
        }
    }

}
new User_Flow_Activate();