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
    // Dependencies
    [
      'tangible-upvoty-frontend-js',
      'wp-block-editor', 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-server-side-render'
    ],
    $version
  );

  // $gutenberg_block localization
  $gutenberg_block = array(
    'specific_board_label'  => __('Show Specific Board', 'upvoty-wp'),
    'specific_board_help'  => __('Whether to show only specific Board.', 'upvoty-wp'),
    'board_hash_label'  => __('Board Hash', 'upvoty-wp'),
    'board_hash_help'  => __('Specific Board Hash - a Board\'s hash can be found in its Widget section: https://tangible.upvoty.com/boards/widget/BOARD_NAME/', 'upvoty-wp'),
    'start_page_label'  => __('Start Page', 'upvoty-wp'),
    'start_page_help'  => __('Whether to show Roadmap as a start page of the specific Board', 'upvoty-wp'),
  );

  wp_localize_script( 'tangible-upvoty-gutenberg-blocks-js', 'UGBLocalized', $gutenberg_block );

  wp_enqueue_style(
    'tangible-upvoty-gutenberg-blocks-css',
    $url . 'assets/build/gutenberg-blocks.min.css',
    [],
    $version
  );
});

/**
 * Enqueue block assets for frontend (also loaded in backend editor)
 */
add_action('enqueue_block_assets', function() use ($upvoty) {
  $upvoty->enqueue_script();
});
