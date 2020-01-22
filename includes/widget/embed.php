<?php

$upvoty->embed_widget = function ( $atts = [], $immediate = true) use ( $upvoty ) {

  // Frontend
  if ($immediate) {
    return $upvoty->embed_widget_init_script( $atts );
  }

  // Admin or page builder

  $action = (is_admin() ? 'admin_' : 'wp_') . 'print_footer_scripts';

  add_action($action, function() use ( $upvoty, $atts ) {
    $upvoty->embed_widget_init_script( $atts );
  });
};

$upvoty->get_embed_widget_data = function( $atts = [] ) use ($upvoty) {

  $settings = $upvoty->get_extended_settings();

  $widget_data = [
    'ssoToken' => $upvoty->generate_user_token(),
    'baseUrl'  => $settings['base_url'],
  ];

  if ( isset($atts['board_hash']) ) {
    $widget_data['boardHash'] = $atts['board_hash'];
  }

  if ( isset($atts['start_page']) ) {
    $widget_data['startPage'] = $atts['start_page'];
  }

  $embed_js_url = $settings['embed_js_url'];

  return [
    'widget_data' => $widget_data,
    'embed_js_url' => $embed_js_url,
  ];
};

$upvoty->get_embed_widget_data_json = function( $atts = [] ) use ($upvoty) {

  $data = $upvoty->get_embed_widget_data( $atts );

  return wp_json_encode([
    'widgetData' => $data['widget_data'],
    'embedJsUrl' => $data['embed_js_url'],
  ]);
};

$upvoty->embed_widget_init_script = function( $atts = [] ) use ($upvoty) {

  ?>
<script>
if (UpvotyWp && UpvotyWp.create) {
  UpvotyWp.create(<?= $upvoty->get_embed_widget_data_json( $atts ) ?>)
}
</script>
  <?php
};
