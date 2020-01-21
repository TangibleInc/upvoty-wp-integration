<?php

/**
 * Third-party integrations
 *
 * Each one should load conditionally when dependencies are met
 *
 * @local $framework, $upvoty
 */

require __DIR__ . '/elementor/index.php';

if ( class_exists('FLBuilder') ) {
  require __DIR__ . '/beaver/index.php';
}

if ( function_exists('has_blocks') ) {
  require __DIR__ . '/gutenberg/index.php';
}
