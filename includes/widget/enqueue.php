<?php

$upvoty->enqueue_script = function() use ($upvoty) {

  $url = $upvoty->plugin->url;
  $version = $upvoty->plugin->version;

  wp_enqueue_script(
    'tangible-upvoty-frontend-js',
    $url . 'assets/build/frontend.min.js',
    ['jquery'],
    $version
  );
};

add_action('wp_enqueue_script', $upvoty->enqueue_script);
