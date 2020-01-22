<?php

namespace Tangible\Upvoty\Integrations\Elementor;

/**
 * Register integration and check dependencies
 */
if ( ! $upvoty->add_integration([
  'name' => 'gutenberg',
  'title' => 'Gutenberg',
  'description' => 'In block category "Embed"',
  'active' => function_exists('has_blocks'),
])) return;

require_once __DIR__.'/enqueue.php';

/**
 * Register dynamic block server-side render
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/creating-dynamic-blocks/
 */
add_action( 'init', function() use ($upvoty) {

  register_block_type(
    'tangible/upvoty', // Block name must match in JS
    [
      'attributes' => [
        'settings' => [
          'type' => 'string',
          'default' => '',
        ],
      ],
      'render_callback' => function($attributes, $content) use ($upvoty) {

        // Prepare shortcode attributes from block settings
        $atts = [];

        // Important: Dynamic embed specifically needed for Gutenberg
        $immediate = false;

        return $upvoty->widget($atts, $immediate);
      }
    ]
  );
});
