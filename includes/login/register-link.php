<?php
/**
 * Based on register link in wp-login.php
 */

?>
<p id="nav">
<?php
if ( get_option( 'users_can_register' ) ) {
  $registration_url = sprintf( '<a class="registration-link" href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register', 'upvoty-wp' ) );
  echo apply_filters( 'register', $registration_url );
  echo esc_html( apply_filters( 'login_link_separator', ' | ' ) );
}
?>
<a class="lost-password-link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'upvoty-wp' ); ?></a>
</p>
