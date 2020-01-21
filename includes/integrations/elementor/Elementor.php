<?php
namespace Tangible\Upvoty\Integrations\Elementor;

defined( 'ABSPATH' ) or die();


/**
 * Main Upvoty WP Elementor Integration Class: Handle the logic with Elementor
 *
 * @see https://developers.elementor.com/creating-an-extension-for-elementor/
 *
 */
final class Elementor {

  /**
   * Minimum Elementor Version
   *
   * @var string Minimum Elementor version required to run the plugin.
   */
  const MINIMUM_ELEMENTOR_VERSION = '2.0.0';


  /**
   * Minimum PHP Version
   *
   * @var string Minimum PHP version required to run the plugin.
   */
  const MINIMUM_PHP_VERSION = '7.0';


  /**
   * Instance
   *
   * @access private
   * @static
   *
   * @var The single instance of the class.
   */
  private static $_instance = null;

  /**
   * Instance
   *
   * Ensures only one instance of the class is loaded or can be loaded.
   *
   * @access public
   * @static
   *
   * @return An instance of the class.
   */
  public static function instance() {

    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;

  }

  /**
   * Constructor
   *
   * @access public
   */
  public function __construct() {

   add_action( 'init', [ $this, 'i18n' ] );

    // Add custom category
    add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );
  }

  /**
   * Initialize the plugin
   *
   * Load the plugin only after Elementor (and other plugins) are loaded.
   * Checks for basic plugin requirements, if one check fail don't continue,
   * if all check have passed load the files required to run the plugin.
   *
   * Fired by `plugins_loaded` action hook.
   *
   * @access public
   */
  public function init( $plugin ) {

    // Required for the widget and dynamic tags definition
    if ( $plugin )  $this -> plugin = $plugin;

    // Check if Elementor installed and activated
    if ( !did_action( 'elementor/loaded' ) ) return;

    // Check for required Elementor version
    if ( !version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
      add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

      return;
    }

    // Check for required PHP version
    if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
      add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

      return;
    }

    // Add Plugin actions
    add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

  }

  /**
   * Load Textdomain
   *
   * Load plugin localization files.
   *
   * Fired by `init` action hook.
   *
   * @access public
   */
  public function i18n() {
    load_plugin_textdomain( 'upvoty-wp-textdomain' );
  }


  /**
   * Admin notice
   *
   * Warning when the site doesn't have Elementor installed or activated.
   *
   * @access public
   */
  public function admin_notice_missing_main_plugin() {

    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

    $message = sprintf(
      esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'upvoty-wp-textdomain' ),
      '<strong>' . esc_html__( 'Upvoty WP Integration for Elementor', 'upvoty-wp-textdomain' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'upvoty-wp-textdomain' ) . '</strong>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

  }

  /**
   * Admin notice
   *
   * Warning when the site doesn't have a minimum required Elementor version.
   *
   * @access public
   */
  public function admin_notice_minimum_elementor_version() {

    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

    $message = sprintf(
      esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'upvoty-wp-textdomain' ),
      '<strong>' . esc_html__( 'Upvoty WP Integration for Elementor', 'upvoty-wp-textdomain' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'upvoty-wp-textdomain' ) . '</strong>',
      self::MINIMUM_ELEMENTOR_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

  }

  /**
   * Admin notice
   *
   * Warning when the site doesn't have a minimum required PHP version.
   *
   *
   * @access public
   */
  public function admin_notice_minimum_php_version() {

    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

    $message = sprintf(
    /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
      esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'upvoty-wp-textdomain' ),
      '<strong>' . esc_html__( 'Upvoty WP Integration for Elementor', 'upvoty-wp-textdomain' ) . '</strong>',
      '<strong>' . esc_html__( 'PHP', 'upvoty-wp-textdomain' ) . '</strong>',
      self::MINIMUM_PHP_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

  }

  /**
   * Add a custom categroy
   *
   * @see https://developers.elementor.com/widget-categories/
   */
  public function add_category( $elements_manager ) {

    $elements_manager->add_category(
      'upvoty-integration',
      [
        'title' => __( 'Upvoty Integration', 'upvoty-wp-textdomain' ),
        'icon' => 'fa fa-plug',
      ]
    );
  }

  /**
   * Init Widgets
   *
   * Include widgets files and register them
   *
   * @access public
   */
  public function init_widgets() {

    // Register widgets
    $widgets_manager = &\Elementor\Plugin::instance()->widgets_manager;

    if( !isset($this->plugin) ) return;

    // Include Widget files
    require_once( __DIR__ . '/widgets/Upvoty.php' );

    // Register widget
    $widgets_manager->register_widget_type( new Widgets\Upvoty() );
  }
}

Elementor::instance();