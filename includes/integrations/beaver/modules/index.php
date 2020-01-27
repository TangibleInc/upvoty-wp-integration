<?php
namespace Tangible\Upvoty\Integrations\Beaver\Modules;

// Load modules.
add_action( 'init', function() {
  require __DIR__ . '/upvoty/Upvoty.php';
});