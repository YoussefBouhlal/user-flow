<?php
/**
 * Template for register form
 *
 * @package user-flow/templates
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

foreach ( $atts['errors'] as $error ) {
    ?>
        <p><?php echo esc_html( $error ); ?></p>
    <?php
}

?>

<div class="register-form-container">

    <?php if ( $atts['show_title'] ) : ?>
        <h2><?php _e( 'Register', USER_FLOW_TEXT_DOMAIN ); ?></h2>
    <?php endif; ?>

    <form action="<?php echo wp_registration_url(); ?>" method="post">

        <p class="form-row">
            <label for="email"><?php _e( 'Email', USER_FLOW_TEXT_DOMAIN ); ?> <strong>*</strong></label>
            <input type="text" name="email" id="email">
        </p>

        <p class="form-row">
            <label for="first_name"><?php _e( 'First name', USER_FLOW_TEXT_DOMAIN ); ?></label>
            <input type="text" name="first_name" id="first-name">
        </p>

        <p class="form-row">
            <label for="last_name"><?php _e( 'Last name', USER_FLOW_TEXT_DOMAIN ); ?></label>
            <input type="text" name="last_name" id="last-name">
        </p>

        <p class="form-row">
            <?php _e( 'Note: Your password will be generated automatically and sent to your email address.', USER_FLOW_TEXT_DOMAIN ); ?>
        </p>

        <p class="signup-submit">
            <input type="submit" name="submit" class="register-button" value="<?php _e( 'Register', USER_FLOW_TEXT_DOMAIN ); ?>"/>
        </p>

    </form>

</div>