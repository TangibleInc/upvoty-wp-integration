<?php

/**
 * Enqueue block assets for backend editor only
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 */
add_action('enqueue_block_editor_assets', function() use ($upvoty) {

  $url = $upvoty->plugin->url;
  $version = $upvoty->plugin->version;

  wp_enqueue_script(
    'tangible-upvoty-gutenberg-blocks-js',
    $url . 'assets/build/gutenberg-blocks.min.js',
    ['wp-block-editor', 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-server-side-render'],
    $version
  );
});

/**
 * Enqueue block assets for frontend (also loaded in backend editor)
 */
add_action('enqueue_block_assets', function() use ($upvoty) {
  $upvoty->enqueue_script();
});
