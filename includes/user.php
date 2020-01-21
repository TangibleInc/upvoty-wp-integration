<?php
/**
 * When a user is logged-in, generate JWT token and pass to widget.
 */

 use \Firebase\JWT\JWT;

 $upvoty->generate_user_token = function( $user = null ) use ( $upvoty ) {

  if ( empty( $user ) ) {
    $user = wp_get_current_user();
  }
  if ( empty( $user ) || empty( $user->ID ) ) {
    return null;
  }

  $settings = $upvoty->get_extended_settings();

  if ( empty( $settings['jwt_private_key'] ) ) {
    return '';
  }
  $private_key = $settings['jwt_private_key'];

  $user_id = $user->ID;
  $u       = get_userdata( $user_id );

  $data = [
    // Required: id, name
    'id'     => $u->ID,
    'name'   => ! empty( $u->display_name )
      ? $u->display_name
      : $u->first_name . ' ' . $u->last_name,
    // Optional but preferred
    'email'  => $u->user_email,
    'avatar' => get_avatar_url( $user_id ),
  ];

  return JWT::encode( $data, $private_key, 'HS256' );
 };
