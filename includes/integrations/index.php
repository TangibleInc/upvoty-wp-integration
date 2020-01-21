<?php

/**
 * Third-party integrations
 *
 * Load conditionally when dependencies are met
 *
 * @local $framework, $upvoty
 */

// if we use Elementor class and namespaces we don't need this ??
/*
if ( did_action( 'elementor/loaded' ) ) {
  require __DIR__ . '/elementor/index.php';
}
*/

if ( class_exists('FLBuilder') ) {
  require __DIR__ . '/beaver/index.php';
}

if ( function_exists('has_blocks') ) {
  require __DIR__ . '/gutenberg/index.php';
}
