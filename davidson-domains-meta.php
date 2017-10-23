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
define('DDM_LIST', 'https://raw.githubusercontent.com/john-michael-murphy/john-michael-murphy.github.io/master/options.php');
define('DDM_', 'HTTP_X_FORWARDED_FOR');

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

            <?php $quotations = get_option('ddm_tags') ?>
            <?php
              for ($x = 0; $x <= sizeof($quotations); $x++) {
                  echo $quotations[$x];
              }
            ?>
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
$quotes = array(
            "The weak can never forgive. Forgiveness is the attribute of the strong",
            "Be strong when you are weak, Be brave when you are scared, Be humble when you are victorious",
            "Our success is achieved by uniting our strength, not by gathering our weaknesses",
            "One of the most common causes of failure is the habit of of quitting when one is overtaken by temporary defeat",
            "The struggles make you stronger and the changes make you wise! Happiness has its own way of taking its sweet time"
            );
add_action('admin_menu', 'ddm_options_page');
update_option('ddm_tags', $quotes, yes);
?>