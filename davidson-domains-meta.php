<?php
/*
Plugin Name: Davidson Domains Meta
Plugin URI: https://github.com/DavidsonCollege/davidson-domains-meta
Description: Opt into Davidson Domains
Author: John-Michael Murphy
Author URI: https://github.com/DavidsonCollege/
Text Domain: davidson-domains-meta
Version: 1.0

*/

define('DDM_LIST', 'https://raw.githubusercontent.com/DavidsonCollege/davidson-domains-meta/master/tags');
//create settings page
add_action('admin_menu', 'ddm_options_page');

//Append ddm_tags to /wp-json
add_filter('rest_index', 'filterResponse');

/* SETTINGS PAGE */
function ddm_options_page_html()
{
  if (!current_user_can('manage_options')) { return; }
  ?>

  <div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    
    
    <h2>Who are you?</h2>
    <br>
    
    <h2>What type of site is this?</h2>
    <br>
    <h2>If you are a student what is your expected year of graduation?</h2>
    
    <?php
    if (get_bloginfo('version') < 4.8){
      ?><p style='color: red'>You are running Wordpress version <?= get_bloginfo('version') ?>. This plugin works only with Wordpress 4.8 or above. </p><?php
    }
    ?>
    <form action="<?= plugins_url('updatesettings.php', __FILE__ ); ?>" method="post">
      <?php
      // Add nonce to form.
      wp_nonce_field('edit_tags', 'ddm_tag_nonce');

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
          <input id="<?=$ddm_tags_remote[$x]?>" name='tags[]' value ='<?=$ddm_tags_remote[$x]?>' type="checkbox" checked>
          <?php
        }

        else {
          ?>
          <input id="<?=$ddm_tags_remote[$x]?>" name='tags[]' value ='<?=$ddm_tags_remote[$x]?>' type="checkbox">
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

//register settings page
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


function filterResponse($response){
  $data = $response->data;
  $data['ddm_tag'] = get_option('ddm_tags');
  $response->set_data($data);
  return $response;
}


?>
