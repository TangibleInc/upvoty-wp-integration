<?php

/**
 * Third-party integrations
 *
 * Each one should load conditionally when dependencies are met
 *
 * @local $framework, $upvoty
 */

$upvoty->integrations = [];

$upvoty->add_integration = function($config) use ($upvoty) {

  // Keep a map for settings page
  $upvoty->integrations[ $config['name'] ] = $config;

  // Return active status for convenience
  return isset($config['active']) && $config['active'];
};

$upvoty->get_integration = function($name) use ($upvoty) {
  return isset($upvoty->integrations[ $name ])
    ? $upvoty->integrations[ $name ]
    : false;
};

require __DIR__ . '/gutenberg/index.php';
require __DIR__ . '/elementor/index.php';
require __DIR__ . '/beaver/index.php';
