<?php

/**
 * Upvoty widget
 *
 * Parameters:
 * - board_hash - Board hash
 * - start_page - Available values: roadmap
 */

$upvoty->widget = function ( $atts = [], $immediate = true ) use ( $upvoty ) {

  if ( $upvoty->widget_loaded ) return;

  $settings = $upvoty->get_extended_settings();

  ob_start();

  if ( ! $settings['is_complete'] ) {

    if (current_user_can('administrator')) {

      $settings_page_url = isset($upvoty->plugin->settings_page_url)
        ? $upvoty->plugin->settings_page_url
        : admin_url( 'options-general.php?page=upvoty-wp-integration-settings' )
      ;

      ?><p>Please make sure to complete <a href="<?=
        $settings_page_url
      ?>">Upvoty WP settings</a>.</p><?php
    }

  } else {

    // If dynamic, pass widget data so it can be instantiated
    $data_attr = $immediate ? '' : $upvoty->get_embed_widget_data_json($atts);

    ?><div data-upvoty="<?= esc_attr($data_attr) ?>"></div><?php

    $upvoty->embed_widget($atts, $immediate);
    $upvoty->widget_loaded = true;
  }

  return ob_get_clean();
};

$upvoty->widget_loaded = false;

/** Upvoty widget - Provide global function for convenience */
function upvoty_wp_widget( $atts = [], $immediate = true ) {
  return upvoty_wp()->widget( $atts, $immediate );
}

require_once __DIR__.'/embed.php';
require_once __DIR__.'/enqueue.php';
