<?php
/*
Plugin Name: Davidson Domains Meta
Plugin URI: https://github.com/DavidsonCollege/davidson-domains-meta
Description: Opt into Davidson Domains
Author: John-Michael Murphy
Author URI: https://github.com/DavidsonCollege/
Text Domain: davidson-domains-meta
Version: 1.2

*/

/* SETTINGS PAGE */
function ddm_options_page_html()
{
  if (!current_user_can('manage_options')) {
    status_header(403);
    ?><h1>Forbidden</h1><?php
    return;
  }
  ?>
  <div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <?php
    if (get_bloginfo('version') < 4.8){
      ?><p style='color: red;'>You are running Wordpress version <?= get_bloginfo('version') ?>. This plugin works only with Wordpress 4.8 or above. </p><?php
    }
    ?>
     <script>
        function onSubmit(){ alert('Your Settings have been Saved!'); }
    </script>
    <form action="<?= plugins_url('updatesettings.php', __FILE__ ); ?>" method="post" onsubmit="return onSubmit()">

  <?php
  // Add nonce to form.
  wp_nonce_field('edit_tags', 'ddm_tag_nonce');

  //Retrieve currently selected settings from WP database
  $ddm_tags = get_option('ddm_tags');
  if ( empty($ddm_tags) ) {
    // If the array doesn't exist, then create an empty one, so in_array doesn't bawk.
    $ddm_tags = [];
  }

  $string = file_get_contents(dirname(__FILE__) . '/tags.json');
  $tags = json_decode($string, true);

  //Iterate through object. Each key becomes a section. Iterate through the associated array.
  foreach ($tags as $key => $attributes) {

    //Section Title
    ?><h3><?=$key?></h3><?php

    //Section attributes
    for ($x = 0; $x < sizeof($attributes); $x++) {

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

      //Checkbox label
      ?><label for="<?=$attributes[$x]?>"> <?=$attributes[$x]?></label><?php

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

// register settings page
add_action('admin_menu', 'davidson_domains_options_page');
function davidson_domains_options_page()
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

//Append ddm_tags to /wp-json
add_filter('rest_index', 'davidson_domains_add_meta_to_endpoint');
function davidson_domains_add_meta_to_endpoint($response){
  $data = $response->data;
  $data['ddm_tag'] = get_option('ddm_tags');
  $response->set_data($data);
  return $response;
}

// if (!wp_verify_nonce($_POST['ddm_tag_nonce'], 'edit_tags')) {
//     status_header(400, "Invalid nonce");
//     return;
// }
//
// $tags = $_POST['tags'];
// update_option('ddm_tags', $tags, yes);
// echo header('Location: ' . $_SERVER['HTTP_REFERER']);

?>
