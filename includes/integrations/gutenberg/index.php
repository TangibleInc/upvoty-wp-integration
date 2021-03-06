<?php

namespace Tangible\Upvoty\Integrations\Gutenberg;

/**
 * Register integration and check dependencies
 */
if ( ! $upvoty->add_integration([
  'name' => 'gutenberg',
  'title' => __( 'Gutenberg', 'upvoty-wp' ),
  'description' => __( 'In block category "Embed"', 'upvoty-wp' ),
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
        'specific_board' => [
          'type' => 'string',
          'default' => '',
        ],
        'board_hash' => [
          'type' => 'string',
          'default' => '',
        ],
        'start_page' => [
          'type' => 'string',
          'default' => '',
        ],

      ],
      'render_callback' => function($attributes, $content) use ($upvoty) {

        // Prepare shortcode attributes from block settings
        $atts = [];
        $atts = $attributes;

        // Important: Dynamic embed specifically needed for Gutenberg
        $immediate = false;

        return $upvoty->widget($atts, $immediate);
      }
    ]
  );
});
