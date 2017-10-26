<?php

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

// Verify that only authorized users can access this.
if (!current_user_can('manage_options')) {
    status_header(403);
    return;
}

// Verify that nonce is valid. Note that the second parameter
// **MUST** be the same as when the nonce was defined in the
// form.
if (!wp_verify_nonce($_POST['ddm_tag_nonce'], 'edit_tags')) {
    status_header(400, "Invalid nonce");
    return;
}

$tags = $_POST['tags'];
update_option('ddm_tags', $tags, yes);
echo header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
