<?php


namespace Tangible\Upvoty\Integrations\Beaver\Modules\Upvoty;


class Upvoty extends \FLBuilderModule {

  /**
   * Constructor
   *
   * @method __construct
   */
  public function __construct() {

    parent::__construct(
      [
        'name'          => __('Upvoty Integration', 'upvoty-wp'),
        'description'   => __('Render Upvoty Boards.', 'upvoty-wp'),
        'category'		  => __('Upvoty Integration Modules', 'upvoty-wp'),
        'dir'           => upvoty_wp() -> plugin -> config['dir_path'] . 'includes/integrations/beaver/modules/upvoty/',
        'url'           => upvoty_wp() -> plugin -> config['url'] . 'includes/integrations/beaver/modules/upvoty/',
      ]
    );
  }


  /**
   *
   * Gets Upvoty content
   *
   * @method get_upvoty
   * @return string
   */
  public function get_upvoty() {

    $settings = $this -> settings;
    $args=[];

    if( $settings -> specific_board ==='yes' && $settings -> board_hash ) {
      $args[ 'board_hash' ] = $settings -> board_hash;
      if ( $settings -> start_page !== 'no' ) {
        $args[ 'start_page' ] = $settings -> start_page;
      }
    }

    return upvoty_wp_widget( $args, true );
  }
}


/**
 * Register the module and its settings.
 */
\FLBuilder::register_module( 'Tangible\\Upvoty\\Integrations\\Beaver\\Modules\\Upvoty\\Upvoty',
[
  'upvoty' =>
    [ // Tab
      'title' => __('Upvoty', 'upvoty-wp'), // Tab title
      'sections' =>
        [ // Tab Sections
          'parameters' =>
            [ // Section
              'title' => __('Parameters', 'upvoty-wp'), // Section Title
              'fields'=>
                [ // Section Fields
                  'specific_board' =>
                    [
                      'type'        => 'select',
                      'label'       => __('Show Specific Board', 'upvoty-wp'),
                      'default'     => 'no',
                      'description' => __('Whether to show only specific Board.', 'upvoty-wp'),
                      'options'     =>
                        [
                          'yes' => __('Yes', 'upvoty-wp'),
                          'no'  => __('No', 'upvoty-wp')
                        ],
                      'toggle' =>
                        [
                          'yes' =>
                            [
                              'fields' =>
                              ['board_hash', 'start_page'],
                            ],
                          'no' =>
                            [

                            ]
                        ]
                    ],
                  'board_hash'   =>
                    [
                      'type'          => 'text',
                      'label' => __( 'Board Hash', 'upvoty-wp' ),
                      'default'       => '',
                      'description' => __('Specific Board Hash - a Board\'s hash can be found in its Widget section: https://tangible.upvoty.com/boards/widget/BOARD_NAME/ ', 'upvoty-wp'),
                    ],
                  'start_page'   =>
                    [
                      'type'          => 'select',
                      'label' 	=> __( 'Start Page', 'upvoty-wp' ),
                      'default'	=> 'no',
                      'description' => __('Whether to show Roadmap as a start page of the specific Board', 'upvoty-wp'),
                      'options'       =>
                        [
                          'roadmap'=> __( 'Roadmap Start Page', 'upvoty-wp' ),
                          'no'		=> __( 'Default Board Page ', 'upvoty-wp' )
                        ],
                    ]
                ]
            ]
        ]
    ]
]
);