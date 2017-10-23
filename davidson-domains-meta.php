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

define('DDM_LIST', 'https://raw.githubusercontent.com/DavidsonCollege/davidson-domains-meta/master/tags');

update_option('ddm_tags', array("Artsy", "Fartsy"), yes);


/* SETTINGS PAGE */
function ddm_options_page_html()
{
  // check user capabilities
  if (!current_user_can('manage_options')) {
    return;
  }
  ?>

  <div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <h2>Tags</h2>
    <form action=".php" method="post">
      <?php

      //TODO Make call to server to dynamically populate this field.
      $ddm_tags_remotes = array("Artsy", "Fartsy", "Nerdy", "Turdy");
      $response = wp_remote_get( DDM_LIST );
      
      if ( is_array( $response ) ) {
        $ddm_tags_remote = explode (',', $response['body']);
      }


      $ddm_tags = get_option('ddm_tags');
      // plugin_dir_path('davidson-domains-meta')
      for ($x = 0; $x < sizeof($ddm_tags_remote); $x++) {
        ?>
        <label for="<?=$ddm_tags_remote[$x]?>"> <?=$ddm_tags_remote[$x]?> </label>
        <?php
        if ( in_array($ddm_tags_remote[$x], $ddm_tags) ){
          ?>
          <input id="<?=$ddm_tags_remote[$x]?>" type="checkbox" checked>
          <?php
        }

        else {
          ?>
          <input id="<?=$ddm_tags_remote[$x]?>" type="checkbox">
          <?php
        }
      }

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

/* UPDATE REST API*/

add_action( 'rest_api_init', function () {
  register_rest_route( 'ddmeta', '/tags', array(
    'methods' => 'GET',
    'callback' => 'ddm_return_tags',
  ) );
} );

function ddm_return_tags(){

  $tags = get_option('ddm_tags');
  return json_encode( $tags );

};

?>