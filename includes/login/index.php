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
 * https://USER_SITE_URL/wp-login.php?interim-login=1&upvoty-widget=1
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

  /**
   * Determine redirect URL
   *
   * 1. The widget redirects the user to the initial login page (here)
   * 2. Login page gets the referer URL in HTTP_REFERER
   *
   *   This points back to the location inside the widget where the login flow started. It's in the format:
   *
   *   https://USER_PREFIX.upvoty.com/front/iframe/BOARD_NAME/
   *
   * 3. Login page sets query parameter `upvoty-widget-redirect-url` with that value
   *
   *   This passes the above URL to subsequent internal redirects for login.
   *
   * 4. Upon login form post and successful login, redirect back to that URL
   */
  $upvoty->widget_redirect_url =
    // See #3
    ! empty( $_REQUEST['upvoty-widget-redirect-url'] ) ? $_REQUEST['upvoty-widget-redirect-url']
      // See #2
      : ( ! empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER']
        // See #1 - Fallback: the widget passes a URL to go back to Upvoty board page (standalone outside the site) -
        : @$_REQUEST['redirectUrl']
      )
  ;

  if ( empty( $upvoty->widget_redirect_url ) ) {
    ?>Redirect URL is required<?php
    return;
  }

  /**
   * Pass query args to various pages/redirects
   */
  $add_url_query_args = function($url) use ($upvoty) {
    if (strpos($url, 'wp-login.php')===false) return $url;
    $url = add_query_arg('interim-login', '1', $url);
    $url = add_query_arg('upvoty-widget', '1', $url);

    // See #3 above
    $url = add_query_arg('upvoty-widget-redirect-url', $upvoty->widget_redirect_url, $url);

    $url = add_query_arg( 'redirectUrl', $upvoty->widget_redirect_url, $url );
    return $url;
  };

  // Add it to all URLs, since there are not enough hooks in wp-login.php to reliably pass query args
  add_filter('site_url', $add_url_query_args, 0, 1);

  $settings = $upvoty->get_extended_settings();

  /**
   * Redirect back to Upvoty after login
   */
  add_filter('login_redirect', function( $redirect_to, $requested_redirect_to, $user ) use ( $upvoty, $settings ) {
    if ( is_wp_error( $user ) ) {
      return $redirect_to;
    }

    $token = $upvoty->generate_user_token( $user );

    // See #4 above
    $url   = $settings['sso_redirect_base_url'] . $token . '/';
    $url   = add_query_arg( 'redirectUrl', $upvoty->widget_redirect_url, $url );

    wp_redirect( $url );
    exit;
  }, 0, 3);

  /**
   * Login screen body and header
   */

  add_filter('login_body_class', function( $classes ) {
    $classes [] = 'upvoty-wp-widget-body';
    return $classes;
  }, 10, 1);

  if (!isset($_REQUEST['interim-login'])) return;

  // Customizations below are necessary for interim login form

  /**
   * Remove "session expired" message that's added by default
   */

  add_filter('wp_login_errors', function($errors) {
    $errors->remove('expired');
    return $errors;
  }, 999, 1);

  /**
   * Display register link in the login form
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
  add_action('wp_print_footer_scripts', function() {

    ?>
<script>
var loginUrl = '<?= $login_url ?>';

var i, links = document.getElementsByTagName('a')

for ( i in links ) {
  if (typeof links[i]==='object' && links[i].href) {
    links[i].target = ''
  }
}
// Prevent going to site home via screen title
document.querySelector('#login h1 a').href = 'javascript:void(0)'

</script><?php
  });

}, 0);
