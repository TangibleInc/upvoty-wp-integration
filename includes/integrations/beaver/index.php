<?php

namespace Tangible\Upvoty\Integrations\Beaver;

/**
 * Register integration and check dependencies
 */
if ( ! $upvoty->add_integration([
  'name' => 'beaver',
  'title' => 'Beaver Builder',
  'description' => 'In module \'Upvoty Module\'',
  'active' => class_exists('FLBuilder'),
])) return;

require __DIR__ . '/modules/index.php';
