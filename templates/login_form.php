<?php
/**
 * Template for login form
 *
 * @package user-flow/templates
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;


foreach ( $atts['errors'] as $error ) {
    ?>
        <p class="login-error"><?php echo esc_html( $error ); ?></p>
    <?php
}

if ( $atts['logged_out'] ) {
    ?>
        <p class="login-info"><?php _e( 'You have signed out. Would you like to sign in again?', USER_FLOW_TEXT_DOMAIN ); ?></p>
    <?php
}

?>

<div class="login-form-container">

    <?php if ( $atts['show_title'] ) : ?>
        <h2><?php _e( 'Sign In', USER_FLOW_TEXT_DOMAIN ); ?></h2>
    <?php endif; ?>
    
    <?php
        wp_login_form( array(
            'label_username'    => __( 'Email', USER_FLOW_TEXT_DOMAIN ),
            'label_log_in'      => __( 'Sign In', USER_FLOW_TEXT_DOMAIN ),
            'redirect'          => $atts['redirect']
        ));
    ?>
    
    <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
        <?php _e( 'Forgot your password?', USER_FLOW_TEXT_DOMAIN ); ?>
    </a>
</div>