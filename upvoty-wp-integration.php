<?php
/**
 * Plugin Name: Upvoty WP Integration
 * Plugin URI: https://wordpress.org/tangibleinc/upvoty-wp-integration
 * Description: Integrate Upvoty user feedback system with WordPress
 * Version: 0.1.3
 * Author: Team Tangible
 * Author URI: https://teamtangible.com
 * License: GPLv2 or later
 * Text Domain: upvoty-wp-textdomain
 */

define( 'UPVOTY_WP_VERSION', '0.1.3' );

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/tangible/plugin-framework/index.php';

use Tangible\Integrations\Elementor\Elementor as elementor;

class UpvotyWP {


  use TangibleObject;

  public $name  = 'upvoty_wp';
  public $state = [];

  function __construct() {
    add_action( tangible_plugin_framework()->ready, [ $this, 'register' ] );
  }

  function register( $framework ) {

    $upvoty = $this;

    $upvoty->plugin = $framework->register_plugin(
      [
        'name'                => 'upvoty-wp-integration',
        'title'               => 'Upvoty WP Integration',
        'setting_prefix'      => 'upvoty_wp',
        'settings_menu_title' => 'Upvoty WP',

        'version'             => UPVOTY_WP_VERSION,
        'file_path'           => __FILE__,
        'base_path'           => plugin_basename( __FILE__ ),
        'dir_path'            => plugin_dir_path( __FILE__ ),
        'url'                 => plugins_url( '/', __FILE__ ),
      ]
    );

    /** Features have $framework and $upvoty in their scope */
    include __DIR__ . '/includes/index.php';

    $elementor = new elementor;

    add_action( 'plugins_loaded', function() use( $elementor, $upvoty ) {

      // Pass data to the elementor instance
      $elementor->init( $upvoty );
    }, 7);
  }
}

/**
 * Get plugin instance
 */
function upvoty_wp() {
  static $o;
  if ( $o ) {
    return $o;
  }
  return $o = new UpvotyWP();
}

upvoty_wp();
