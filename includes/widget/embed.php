<?php

$upvoty->embed_widget = function ( $atts = [], $immediate = true) use ( $upvoty ) {

  // Destructure data to variables
  foreach ($upvoty->get_embed_widget_data() as $key => $value) {
    $$key = $value;
  }

  // Frontend
  if ($immediate) {
    return $upvoty->embed_widget_init_script($widget_data, $embed_js_url);
  }

  // Admin or page builder

  $action = (is_admin() ? 'admin_' : 'wp_') . 'print_footer_scripts';

  add_action($action, function() use ($upvoty, $widget_data, $embed_js_url) {
    $upvoty->embed_widget_init_script($widget_data, $embed_js_url);
  });
};

$upvoty->get_embed_widget_data = function() use ($upvoty) {

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

$upvoty->embed_widget_init_script = function($widget_data, $embed_js_url) use ($upvoty) {

  ?>
<script>
if (UpvotyWp && UpvotyWp.create) {
  UpvotyWp.create({
    widgetData: <?= wp_json_encode( $widget_data ) ?>,
    embedJsUrl: '<?= $embed_js_url ?>'
  })
}
</script>
  <?php
};
