<?php

$upvoty->plugin->register_admin_notice(function() use ($framework, $upvoty) {

  $welcome_notice_key = 'upvoty_wp_welcome_notice';

  if ($framework->is_admin_notice_dismissed( $welcome_notice_key )) return;

  if ($upvoty->plugin->is_settings_page()) {
    $framework->dismiss_admin_notice( $welcome_notice_key );
    return;
  }

  $plugin_title = $upvoty->plugin->config['title'];
  $settings_url = $upvoty->plugin->get_settings_page_url();

  ?>
  <div class="notice notice-info is-dismissible" data-tangible-admin-notice="<?= $welcome_notice_key ?>">
    <p>Welcome to <?=$plugin_title  ?>!</p>
    <p>Get started by <a href="<?= $settings_url ?>">completing the settings</a>.</p>
  </div>
  <?php

});
