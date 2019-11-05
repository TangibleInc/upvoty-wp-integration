<?php
/**
 * Single sign-on screen using WordPress internal feature called "interim" login
 *
 * It's a screen without site header/footer, suitable for an iframe. As you will see below,
 * there are some necessary hooks to seamlessly integrate inside the widget.
 *
 * @see wp-login.php
 * @see https://USER_PREFIX.upvoty.com/customers/accountSettings/auth/
 *
 * The URL for this login screen must be defined in Upvoty's Remote SSO settings:
 *
 * https://USER_PREFIX.upvoty.com/customers/accountSettings/remote-sso/
 *
 * The Redirect URL should have the following format:
 *
 * https://USER_SITE_URL/wp-login.php?interim-login=1&upvoty-widget=1&upvoty-redirect-url=https://USER_SITE_URL/feedback
 *
 * When a user is not logged in and tries to create a new feedback post, the widget will
 * redirect to the above URL, displaying this custom login screen.
 */

add_action('login_init', function() use ( $upvoty ) {

  /**
   * Only for login screen inside an Upvoty widget
   */
  $is_upvoty_widget = ! empty( $_REQUEST['upvoty-widget'] );
  if ( ! $is_upvoty_widget ) {
    return;
  }

  $upvoty->widget_redirect_url =
    ! empty( $_REQUEST['upvoty-redirect-url'] )
      // From Upvoty SSO settings, and passed via login/register form redirect
      ? $_REQUEST['upvoty-redirect-url']
      // From Upvoty directly
      : @$_REQUEST['redirectUrl'];

  if ( empty( $upvoty->widget_redirect_url ) ) {
    ?>Redirect URL is required<?php
    return;
  }

  /**
   * Redirect back to Upvoty after login
   */
  add_filter('login_redirect', function( $redirect_to, $requested_redirect_to, $user ) use ( $upvoty ) {
    if ( is_wp_error( $user ) ) {
      return $redirect_to;
    }

    $token = $upvoty->generate_user_token( $user );
    $url   = $upvoty->sso_redirect_url . $token . '/';
    $url   = add_query_arg( 'redirectUrl', $upvoty->widget_redirect_url, $url );

    wp_redirect( $url );
    exit;
  }, 999, 3);

  /**
   * Display register link in the interim login form
   *
   * Since there's no action/filter that does exactly what we need, use the closest filter
   * which requires an awkward workaround.
   */
  add_filter('enable_login_autofocus', function( $enabled ) {

    if ( isset( $_GET['checkemail'] ) && in_array( $_GET['checkemail'], [ 'confirm', 'newpass' ] ) ) {
      return $enabled;
    }

    // We're *inside* a script tag in this filter
    ?></script><?php
    include __DIR__ . '/register-link.php';
    ?><script><?php

    return $enabled;
  }, 999, 1);

  /**
   * Login screen body and header
   */

  add_filter('login_body_class', function( $classes ) {
    $classes [] = 'upvoty-wp-widget-body';
    return $classes;
  }, 10, 1);

  add_filter('login_headertitle', function( $title ) use ( $action ) {
    if ( $action === 'register' ) {
      return 'Register';
    }
    if ( $action === 'lostpassword' ) {
      return 'Recover password';
    }
    return 'Sign in';
  }, 999, 1);

  /**
   * Style override
   * Make screen title not a link - normally it links to site home
   */
  add_action('login_head', function() {
    ?><style><?php
    include __DIR__ . 'style.css';
    ?></style><?php
  });

  /**
   * There's a script for interim login that forces target="_blank"
   * Remove them to keep user inside widget
   */
  add_action('login_footer', function() {
    ?><script><?php
    include __DIR__ . 'script.js';
    ?></script><?php
  });

});
