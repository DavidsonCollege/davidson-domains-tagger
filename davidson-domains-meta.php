<?php
/*
Plugin Name: Davidson Domains Meta
Plugin URI: https://github.com/DavidsonCollege/davidson-domains-meta
Description: Opt into Davidson Domains
Author: John-Michael Murphy and Joe Bannerman
Author URI: https://github.com/DavidsonCollege/
Text Domain: davidson-domains-meta
Version: 1.0

*/

define('DDM_LIST', 'https://raw.githubusercontent.com/DavidsonCollege/davidson-domains-meta/upgrade/tags');
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

    <?php
    if (get_bloginfo('version') < 4.8){
      ?><p style='color: red'>You are running Wordpress version <?= get_bloginfo('version') ?>. This plugin works only with Wordpress 4.8 or above. </p><?php
    }
    ?>
     <script>
        function saveSettings(){alert('Your Settings have been Saved!')}
    </script>
    <form action="<?= plugins_url('updatesettings.php', __FILE__ ); ?>" method="post" onsubmit="return saveSettings()">

  <?php
  // Add nonce to form.
  wp_nonce_field('edit_tags', 'ddm_tag_nonce');

  //Retrieve currently selected settings from WP database
  $ddm_tags = get_option('ddm_tags');

  //Retrieve JSON from CDN (GitHub in our case)
  $response = wp_remote_retrieve_body( DDM_LIST );

  //Convert JSON object to PHP object
  $response = json_decode($response);
  // See http://php.net/manual/en/function.json-decode.php

  //Iterate through object. Each key becomes a section. Iterate through the associated array.
  foreach ($response as $key => $attributes) {

    //Section Title
    ?><h3><?=$key?></h3><?php

    //Section attributes
    for ($x = 0; $x < sizeof($attributes); $x++) {

      //Checkbox label
      ?><label for="<?=$attributes[$x]?>"> <?=$attributes[$x]?></label><?php

      //Check the box if user has previously checked
      if ( in_array($attributes[$x], $ddm_tags) ){
        ?>
        <input id="<?=$attributes[$x]?>" name='tags[]' value ='<?=$attributes[$x]?>' type="checkbox" checked>
        <?php
      }

      //Don't check the box if user hasn't previously checked
      else {
        ?>
        <input id="<?=$attributes[$x]?>" name='tags[]' value ='<?=$attributes[$x]?>' type="checkbox">
        <?php
      }

    }

  }



  //Save on subject
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
