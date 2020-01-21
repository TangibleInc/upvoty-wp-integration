<?php

namespace Tangible\Upvoty\Integrations\Elementor;

/**
 * Register integration and check dependencies
 */
if ( ! $upvoty->add_integration([
  'name' => 'gutenberg',
  'title' => 'Gutenberg',
  'active' => false // function_exists('has_blocks'),
])) return;
