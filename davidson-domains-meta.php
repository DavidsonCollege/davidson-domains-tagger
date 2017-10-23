<?php
/*
  Plugin Name: Davidson Domains Meta
  Plugin URI: https://github.com/DavidsonCollege/davidson-domains-meta
  Description: Tag Sort
  Author: John-Michael Murphy, Joe Bannerman
  Author URI: https://github.com/DavidsonCollege/
  Text Domain: davidson-domains-meta
  Version: 0.1

*/

/*
 * Constants
 */

/* Different ways to get remote address: direct & behind proxy */
define('LIMIT_LOGIN_DIRECT_ADDR', 'REMOTE_ADDR');
define('LIMIT_LOGIN_PROXY_ADDR', 'HTTP_X_FORWARDED_FOR');

/* Notify value checked against these in limit_login_sanitize_variables() */
define('LIMIT_LOGIN_LOCKOUT_NOTIFY_ALLOWED', 'log,email');


function ddm_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
          <label for='artsy'>Artsy</label>
          <input id="artsy" type="checkbox">
          <label for='fartsy'>Fartsy</label>
          <input id="fartsy" type="checkbox">
          <label for='nerdy'>Nerdy</label>
          <input id="nerdy" type="checkbox">
          <label for='turdy'>Turdy</label>
          <input id="turdy" type="checkbox">

            <?php
            // output security fields for the registered setting "wporg_options"
            settings_fields('wporg_options');
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections('wporg');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

function ddm_options_page()
{
    add_submenu_page(
        'tools.php',
        'Davidson Domains Meta',
        'Davidson Domains Meta',
        'manage_options',
        'davidson-domains-meta',
        'ddm_options_page_html'
    );
}
add_action('admin_menu', 'ddm_options_page');
?>