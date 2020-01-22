<?php

namespace Tangible\Upvoty\Integrations\Elementor;

/**
 * Register integration and check dependencies
 */
if ( ! $upvoty->add_integration([
  'name' => 'elementor',
  'title' => 'Elementor',
  'description' => 'In widget category "Upvoty Integration"',
  'active' => defined( 'ELEMENTOR_VERSION' )
    && version_compare( ELEMENTOR_VERSION, '2.0.0', '>=' )
    && did_action( 'elementor/loaded' ),
])) return;

/**
 * Add a custom categroy
 *
 * @see https://developers.elementor.com/widget-categories/
 */
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {

  $elements_manager->add_category(
    'upvoty-integration',
    [
      'title' => __( 'Upvoty Integration', 'upvoty-wp-textdomain' ),
      'icon' => 'fa fa-plug',
    ]
  );
});

/**
 * Register widgets
 */
add_action( 'elementor/widgets/widgets_registered', function() use ($upvoty) {

  $widgets_manager = &\Elementor\Plugin::instance()->widgets_manager;

  require_once __DIR__ . '/widgets/Upvoty.php';

  $widgets_manager->register_widget_type( new Widgets\Upvoty() );
});
