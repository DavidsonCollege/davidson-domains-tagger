<?php
/*
Plugin Name: Davidson Domains Tagger
Plugin URI: https://github.com/DavidsonCollege/davidson-domains-tagger
Description: Opt into Davidson Domains
Author: John-Michael Murphy
Author URI: https://github.com/DavidsonCollege/
Text Domain: davidson-domains-tagger
Version: 1.3.0

*/


add_action('init','davidson_domains_do');
function davidson_domains_do(){
  // register settings page
  add_action('admin_menu', 'davidson_domains_options_page');
  function davidson_domains_options_page()
  {
    add_submenu_page(
      'tools.php',
      'Davidson Domains Tagger',
      'Davidson Domains',
      'manage_options',
      'davidson-domains-tagger',
      'davidson_domains_settings_page'
    );
  }

  // Register form fields in database.
  if(current_user_can('manage_options')) {
    add_action( 'admin_init', 'davidson_domain_register_field' );
  }

  function davidson_domain_register_field() {
    register_setting( 'davidson-domains-tagger', 'davidson-domains-tags', 'davidson_domains_sanitize');
  }

  function davidson_domains_settings_page()
  {
    if (!current_user_can('manage_options')) {
      status_header(403);
      ?><h1>Forbidden</h1><?php
      return;
    }

    $string = file_get_contents(dirname(__FILE__) . '/tags.json');
    $all_tags = json_decode($string, true);
    //Retrieve currently selected settings from WP database
    $selected_tags = get_option('davidson-domains-tags');
    // If the array doesn't exist, then create an empty one, so in_array doesn't bawk.
    if ( empty($selected_tags) ) { $selected_tags = []; }
    else { $selected_tags = davidson_domains_sanitize($selected_tags); }

    ?>
    <div class="wrap">
      <h1><?= esc_html(get_admin_page_title()); ?></h1>
      <p>Tag your site with this plugin to be featured on the <a target="_blank" href="https://domains.davidson.edu/community/">Davidson Domains Community Portal</a>.</p>
      <?php
      if (get_bloginfo('version') < 4.8){
        ?><p style='color: red;'>You are running Wordpress version <?= get_bloginfo('version') ?>. This plugin works only with Wordpress 4.8 or above. </p><?php
      }
      ?>
      <form action="options.php" method="post">
        <?php if( isset($_GET['settings-updated']) ) { ?>
          <div class="notice notice-success is-dismissible">
            <p><?php _e('Saved! Your site is tagged and ready to for prime time!'); ?></p>
          </div>
          <?php
        }
        settings_fields('davidson-domains-tagger');
        do_settings_sections('davidson-domains-tagger');
        //Iterate through object. Each key becomes a section. Iterate through the associated array.
        foreach ($all_tags as $key => $attributes) {
          //Section Title
          ?><h3><?=$key?></h3><?php
          //Section attributes
          for ($x = 0; $x < sizeof($attributes); $x++) {
            //Check the box if user has previously checked
            if ( in_array($attributes[$x], $selected_tags) ){
              ?>
              <input id="<?=esc_attr($attributes[$x])?>" name='davidson-domains-tags[]' value ='<?=esc_attr($attributes[$x])?>' type="checkbox" checked>
              <?php
            }
            //Don't check the box if user hasn't previously checked
            else {
              ?>
              <input id="<?=esc_attr($attributes[$x])?>" name='davidson-domains-tags[]' value ='<?=esc_attr($attributes[$x])?>' type="checkbox">
              <?php
            }
            //Checkbox label
            ?><label for="<?=$attributes[$x]?>"> <?=$attributes[$x]?></label><?php
          }
        }
        submit_button('Save Tags');
        ?>
      </form>
    </div>
    <?php
  }

  //Append ddm_tags to /wp-json
  add_filter('rest_index', 'davidson_domains_add_tags_to_endpoint');
  function davidson_domains_add_tags_to_endpoint($response){
    $data = $response->data;
    $data['ddm_tag'] = get_option('davidson-domains-tags');
    $response->set_data($data);
    return $response;
  }

  function davidson_domains_sanitize($input){
    return array_map('sanitize_text_field', wp_unslash($input));
  }
}
?>
