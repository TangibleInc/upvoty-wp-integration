<?php

namespace Tangible\Upvoty\Integrations\Elementor\Widgets;
/**
*
* Extends the default Elementor Button widget
*
* @see https://developers.elementor.com/creating-a-new-widget/
*/
class Upvoty extends \Elementor\Widget_Base {

  /**
   * Widget slug
   *
   * @return string
   */
  public function get_name() {
    return 'upvoty-integration';
  }


  /**
   * Widget name
   *
   * @return string
   */
  public function get_title() {
    return __( 'Upvoty Integration', 'upvoty-wp' );
  }


  /**
   * @see https://developers.elementor.com/widget-categories/
   *
   * @return array
   */
  public function get_categories() {
    return array( 'upvoty-integration' );
  }


  /**
   * Class name of an eicon or font-awesome icon
   *
   * @see https://elementor.github.io/elementor-icons/
   *
   * @return string
   */
  public function get_icon() {
    return "eicon-time-line";
  }


  /**
   * @see https://developers.elementor.com/elementor-controls/
   */
  protected function _register_controls() {

    $this->start_controls_section(
      'content_section',
      [
        'label' => __( 'Content', 'upvoty-wp' ),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'specific_board',
      [
        'label' => __( 'Show Specific Board', 'upvoty-wp' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __( 'Yes', 'upvoty-wp' ),
        'label_off' => __( 'No', 'upvoty-wp' ),
        'return_value' => 'yes',
        'default' => 'no',
        'description' => __('Whether to show only specific Board.', 'upvoty-wp'),
      ]
    );

    $this->add_control(
      'board_hash',
      [
        'label' => __( 'Board Hash', 'upvoty-wp' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
        'description' => __('Specific Board Hash - a Board\'s hash can be found in its Widget section: https://tangible.upvoty.com/boards/widget/BOARD_NAME/ ', 'upvoty-wp'),
        'condition' => ['specific_board' => 'yes']
      ]
    );

    $this -> add_control(
      'start_page',
      [
        'label' 	=> __( 'Start Page', 'upvoty-wp' ),
        'type' 		=> \Elementor\Controls_Manager::SELECT,
        'label_block' => true,
        'default'	=> 'no',
        'description' => __('Whether to show Roadmap as a start page of the specific Board', 'upvoty-wp'),
        'options'	=>
          [
            'roadmap'=> __( 'Roadmap Start Page', 'upvoty-wp' ),
            'no'		=> __( 'Default Board Page ', 'upvoty-wp' )
          ],
        'condition' => ['specific_board' => 'yes']
      ]
    );

    $this->end_controls_section();
  }


  /**
   * Gets upvoty widget content.
   *
   * @return     string Upvoty widget content.
   */
  protected function get_upvoty() {

    $settings = $this->get_settings_for_display();
    $args=[];

    if( $settings['specific_board'] ==='yes' && $settings[ 'board_hash' ] ) {
      $args[ 'board_hash' ] = $settings[ 'board_hash' ];
      if ( $settings[ 'start_page' ] !== 'no' ) {
        $args[ 'start_page' ] = $settings[ 'start_page' ];
      }
    }

    return upvoty_wp_widget( $args );
  }


  /**
   * Front-end render
   *
   * @return string
   */
  public function render() {

    $content = $this->get_upvoty();

    if (empty($content) && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
      $content = '<p>Upvoty widget will be embedded on the frontend.</p>';
    }

    ?>
    <div class="upvoty-content">
      <?= $content ?>
    </div>
    <?php
  }
}
