<?php

namespace Tangible\Integrations\Elementor\Widgets;
/**
*
* Extends the default Elementor Button widget
*
* @see https://developers.elementor.com/creating-a-new-widget/
*/
Class Upvoty extends \Elementor\Widget_Base
{


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
    return __( 'Upvoty Integration', 'upvoty-wp-textdomain' );
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
        'label' => __( 'Content', 'upvoty-wp-textdomain' ),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'specific_board',
      [
        'label' => __( 'Show Specific Board', 'upvoty-wp-textdomain' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __( 'Yes', 'upvoty-wp-textdomain' ),
        'label_off' => __( 'No', 'upvoty-wp-textdomain' ),
        'return_value' => 'yes',
        'default' => 'no',
        'description' => __('Whether to show only specific Board.', 'upvoty-wp-textdomain'),
      ]
    );

    $this->add_control(
      'board_hash',
      [
        'label' => __( 'Board Hash', 'upvoty-wp-textdomain' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
        'description' => __('Specific Board Hash - a Board\'s hash can be found in its Widget section: https://tangible.upvoty.com/boards/widget/BOARD_NAME/ ', 'upvoty-wp-textdomain'),
        'condition' => ['specific_board' => 'yes']
      ]
    );

    $this -> add_control(
      'start_page',
      [
        'label' 	=> __( 'Start Page', 'upvoty-wp-textdomain' ),
        'type' 		=> \Elementor\Controls_Manager::SELECT,
        'label_block' => true,
        'default'	=> 'no',
        'description' => __('Whether to show Roadmap as a start page of the specific Board', 'upvoty-wp-textdomain'),
        'options'	=>
          [
            'roadmap'=> __( 'Roadmap Start Page', 'lifter-elements' ),
            'no'		=> __( 'Default Board Page ', 'lifter-elements' )
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

    if( $settings['specific_board'] ==='yes' ) {
      if ( $settings[ 'board_hash' ] ) {
        $args[ 'board_hash' ] = $settings[ 'board_hash' ];
         if ( $settings[ 'start_page' ] !== 'no' ){
           $args[ 'start_page' ] = $settings[ 'start_page' ];
         }
      }
    }

    return upvoty_wp_widget( $args );
    //return upvoty_wp_widget( ['start_page' =>'roadmap', 'board_hash' => '304657fa2cdbd0b4c674672bb256ece01e7d1725ddadee6038385faacf6b50c6'], true );
  }


  /**
   * Front-end render
   *
   * @return string
   */
  public function render() {

    $content = $this->get_upvoty();

    if( !ctype_space(strip_tags($content)) && !empty(strip_tags($content)) ) {
    ?>
      <div class="upvoty-content">
        <?php
          echo $content;
        ?>
      </div>
    <?php

    // Preview in the builder if no content
    } else if( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
      ?>
      <div class="upvoty-content">
        <h2><?php echo __( 'This is preview content', 'upvoty-wp-textdomain') ?></h2>
        <p><?php echo __( 'Upvoty content couldn\'t be loaded in editor mode', 'upvoty-wp-textdomain') ?></p>
      </div>
      <?php
    }
  }
}