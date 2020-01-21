<?php

$upvoty->widget_loaded = false;

/**
 * Upvoty widget
 *
 * Parameters:
 * - board_hash - Board hash
 * - start_page - Available values: roadmap
 */

$upvoty->widget = function ( $atts = [], $return = false ) use ( $upvoty ) {

  if ( $upvoty->widget_loaded ) {
    return;
  }
  $upvoty->widget_loaded = true;

  $settings = $upvoty->get_extended_settings();

  if ( empty( $settings['jwt_private_key'] ) ) {

    if ( $return ) ob_start();

    if (current_user_can('administrator')) {

      $settings_page_url = isset($upvoty->plugin->settings_page_url)
        ? $upvoty->plugin->settings_page_url
        : admin_url( 'options-general.php?page=upvoty-wp-integration-settings' )
      ;

      ?><p>Please make sure to complete <a href="<?=
        $settings_page_url
      ?>">Upvoty WP settings</a>.</p><?php
    }

    if ( $return ) return ob_get_clean();
    return;
  }

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

  /**
   * Maybe in the future, support mapping board name to hash
   *
  if ( ! empty( $atts['board'] ) ) {

    $board_name = $atts['board'];
    $board_hash = isset( $upvoty->boards[ $board_name ] )
      ? $upvoty->boards[ $board_name ]
      : '';

    if ( ! empty( $board_hash ) ) {
      $widget_data['boardHash'] = $board_hash;
    }
  }
  */

  if ( $return ) {
    ob_start();
  }

  ?>
<div data-upvoty></div>

<script type='text/javascript'>
(function() {

  var script = document.createElement('script')
  var onError = function() {
    document.querySelector('[data-upvoty]').innerText = 'Upvoty widget could not be loaded.'
  }
  var onLoad = function() {
    if (window.upvoty == undefined) return onError()
    upvoty.init('render', <?= wp_json_encode( $widget_data ) ?>)
  }

  script.onerror = onError
  script.onload = onLoad
  script.onreadystatechange = onLoad
  script.src = '<?= esc_attr( $settings['embed_js_url'] ) ?>'

  document.body.appendChild(script)

  if (window.upvoty) return onLoad()
})()
</script>
  <?php

  if ( $return ) {
    return ob_get_clean();
  }
};

/** Upvoty widget - Provide global function for convenience */
function upvoty_wp_widget( $atts = [], $return = false ) {
  return upvoty_wp()->widget( $atts, $return );
}
