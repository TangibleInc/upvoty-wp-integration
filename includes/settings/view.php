<?php
// local: $settings, $settings_key
/**
 * Settings screen
 */

$user_url_prefix = @$settings['upvoty_user_url_prefix'];

$jwt_private_key_url =
  'https://'
  . ( ! empty( $user_url_prefix ) ? $user_url_prefix : 'USER_PREFIX' )
  . '.upvoty.com/customers/accountSettings/auth/';

$remote_sso_settings_url_suffix = 'upvoty.com/customers/accountSettings/remote-sso/';

?>
<div class="row">
  <fieldset class="col col-md-6">
    <label for="">Upvoty User</label>
    <input type="text"
      name="<?= "{$settings_key}[upvoty_user_url_prefix]" ?>"
      value="<?= $user_url_prefix ?>"
      placeholder="username"
      autocomplete="off"
    />.upvoty.com
  </fieldset>

  <fieldset class="col col-md-6">
    <label for="">Private Key for JSON Web Token</label>
    <input type="text"
      class="field-jwt-private-key font-dots"
      name="<?= "{$settings_key}[jwt_private_key]" ?>"
      value="<?= @$settings['jwt_private_key'] ?>"
      autocomplete="off"
    />
  </fieldset>
</div>
<?php submit_button(); ?>
<hr>

<fieldset>
  <label for="">How to Use</label>
  <ol>
    <li>
      <p>Enable <b>single sign-on</b> for visitors to register/login through your site within the widget.</p>
      <ol>
        <li>
          <p>
            Go to Upvoty customer settings for <b>Remote SSO</b>.
          </p>
          <p>
          <?=
          empty( $user_url_prefix ) ? "https://USER_PREFIX.{$remote_sso_settings_url_suffix}"
            : <<<HTML

<a href="https://{$user_url_prefix}.{$remote_sso_settings_url_suffix}" target="_blank">
  https://{$user_url_prefix}.{$remote_sso_settings_url_suffix}
</a>

HTML
          ?>
          </p>
        </li>
        <li>
          <p>Enter the following URL for <b>Dedicated Login</b> page:</p>
          <pre><code><?= site_url() ?>/wp-login.php?interim-login=1&upvoty-widget=1</code></pre>
        </li>
      </ol>
    </li>
    <li>
      <p>Generate a <b>private key</b>, and enter it in the setting field above.</p>
      <p><?=
      empty( $user_url_prefix ) ? $jwt_private_key_url
        : <<<HTML

<a href="$jwt_private_key_url" target="_blank">$jwt_private_key_url</a>

HTML
      ?>
      </p>
    </li>
    <li>
      <p>Use the shortcode <code>[upvoty-wp]</code> to display the widget.</p>
    </li>
  </ol>
</fieldset>
