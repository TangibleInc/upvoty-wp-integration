<?php

namespace Tangible\Upvoty\Integrations\Beaver;

/**
 * Register integration and check dependencies
 */
if ( ! $upvoty->add_integration([
  'name' => 'beaver',
  'title' => __( 'Beaver Builder', 'upvoty-wp' ),
  'description' => __( 'In module "Upvoty Module"', 'upvoty-wp' ),
  'active' => class_exists('FLBuilder'),
])) return;

require __DIR__ . '/modules/index.php';
