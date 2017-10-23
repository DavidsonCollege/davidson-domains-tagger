<?php
/*
Plugin Name: Davidson Domains Meta
Plugin URI: https://github.com/DavidsonCollege/davidson-domains-meta
Description: Tag Sort
Author: John-Michael Murphy
Author URI: https://github.com/DavidsonCollege/
Text Domain: davidson-domains-meta
Version: 0.1

*/

define('DDM_LIST', 'https://raw.githubusercontent.com/DavidsonCollege/davidson-domains-meta/master/tags');

update_option('ddm_tags', array("Artsy", "Fartsy"), yes);


/* SETTINGS PAGE */
function ddm_options_page_html()
{
  if (!current_user_can('manage_options')) { return; }
  ?>

  <div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <h2>Tags</h2>
    <form action="options.php" method="post">
      <?php

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

      settings_fields('wporg_options');
      do_settings_sections('wporg');
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

/* ADD TAGS ROUTE TO API*/

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