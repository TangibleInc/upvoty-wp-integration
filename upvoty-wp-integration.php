<?php
/**
 * Plugin Name: Upvoty WP Integration
 * Plugin URI: https://wordpress.org/tangibleinc/upvoty-wp-integration
 * Description: Integrate Upvoty user feedback system with WordPress
 * Version: 0.1.5
 * Author: Team Tangible
 * Author URI: https://teamtangible.com
 * License: GPLv2 or later
 * Text Domain: upvoty-wp
 */

define( 'UPVOTY_WP_VERSION', '0.1.5' );

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/tangible/plugin-framework/index.php';

class UpvotyWP {

  use TangibleObject;

  public $name  = 'upvoty_wp';
  public $state = [];

  function __construct() {

    add_action( tangible_plugin_framework()->ready, [ $this, 'register' ] );

    /**
     * Load plugin textdomain and localization files
     */
    add_action( 'init', function() {
      load_plugin_textdomain( 'upvoty-wp' );
    });
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

    /** Features have $framework and $upvoty in their local scope */
    include __DIR__ . '/includes/index.php';
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
