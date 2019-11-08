<?php

/** Upvoty widget */
$upvoty->widget = function ($atts = [], $return = false) use ($framework, $upvoty) {

  $settings = $upvoty->get_extended_settings();

  $widget_data = [
  'ssoToken' => $upvoty->generate_user_token(),
  'baseUrl'  => $settings['base_url'],
  ];

  if ( ! empty( $atts['board'] ) ) {

    $board_name = $atts['board'];
    $board_hash = isset( $upvoty->boards[ $board_name ] )
      ? $upvoty->boards[ $board_name ]
      : '';

    if ( ! empty( $board_hash ) ) {
      $widget_data['boardHash'] = $board_hash;
    }
  }

  if ($return) ob_start();

  ?>

<div data-upvoty></div>

<script id="upvoty-embed-script" type='text/javascript' src='<?= esc_attr( $settings['embed_js_url'] ) ?>'></script>
<script type='text/javascript'>
(function() {

  var src = document.getElementById('upvoty-embed-script')
  var onError = function() {
    document.querySelector('[data-upvoty]').innerText = 'Upvoty widget could not be loaded.'
  }
  if (window.upvoty == undefined) {
    return onError()
  }

  upvoty.init('render', <?= wp_json_encode( $widget_data ) ?>)

})()
</script>
  <?php

  if ($return) {
    return ob_get_clean();
  }
};

/** Upvoty widget - Provide global function for convenience */
function upvoty_wp_widget($atts = [], $return = false) {
  upvoty_wp()->widget($atts, $return);
}
