<?php

include_once TEMPLATEPATH . '/lib/metaboxes/wpalchemy/MetaBox.php';
include_once TEMPLATEPATH . '/lib/metaboxes/wpalchemy/MediaAccess.php';
 
// include css to help style our custom meta boxes
add_action( 'init', 'studiofolio_metabox_styles' );
 
function studiofolio_metabox_styles()
{
    if ( is_admin() )
    {
        wp_enqueue_style( 'wpalchemy-metabox', get_template_directory_uri() . '/lib/metaboxes/meta.css' );
        wp_enqueue_style( 'tinymce',get_template_directory_uri() . '/lib/admin/css/tinymce.css' );
        wp_enqueue_style('farbtastic');
        wp_enqueue_script('farbtastic');
    }
}
 
$wpalchemy_media_access = new WPAlchemy_MediaAccess();

/* eof */