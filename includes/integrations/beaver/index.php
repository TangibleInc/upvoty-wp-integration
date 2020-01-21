<?php

namespace Tangible\Upvoty\Integrations\Beaver;

/**
 * Register integration and check dependencies
 */
if ( ! $upvoty->add_integration([
  'name' => 'beaver',
  'title' => 'Beaver Builder',
  'active' => class_exists('FLBuilder'),
])) return;
