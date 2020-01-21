<?php

$upvoty->get_extended_settings = function() use ( $upvoty ) {

  $settings = $upvoty->plugin->get_settings();

  $url_prefix = !empty( $settings['upvoty_user_url_prefix'])
    ? $settings['upvoty_user_url_prefix']
    : ''
  ;

  $upvoty_base_url = (!empty($url_prefix) ? $url_prefix : 'USER').'.upvoty.com';

  $jwt_private_key = !empty($settings['jwt_private_key'])
    ? $settings['jwt_private_key']
    : ''
  ;

  return array_merge($settings, [
    'base_url'              => $upvoty_base_url,
    'embed_js_url'          => "https://{$upvoty_base_url}/javascript/upvoty.embed.js",
    'sso_redirect_base_url' => "https://{$upvoty_base_url}/front/handleSSO/",
    'jwt_private_key'       => $jwt_private_key,

    /**
     * Ensure all required fields
     */
    'is_complete'           => !empty($url_prefix) && !empty($jwt_private_key),

    /**
     * Allow user to associate board name to hash
     *
     * User must get the hash from the board's widget settings:
     *
     * https://USER_PREFIX.upvoty.com/boards/widget/BOARD_NAME/
     */
    'boards'                => [],
  ]);
};

$upvoty->plugin->register_settings(
  [
    'css'  => $upvoty->plugin->url . 'assets/build/admin-settings.min.css',
    'tabs' => [
      [
        'title'    => 'Settings',
        'callback' => function( $plugin_config, $settings, $settings_key ) use ( $framework, $upvoty ) {
          include __DIR__ . '/view.php';
        },
      ],
    ],
  ]
);
