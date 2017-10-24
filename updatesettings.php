<?php
require( $_SERVER['DOCUMENT_ROOT'] .'/wp-load.php' );

$tags = $_POST['tags'];
update_option('ddm_tags', $tags, yes);
echo header('Location: ' . $_SERVER['HTTP_REFERER']);
?>