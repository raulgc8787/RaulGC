<?php

function studiofolio_setup() {

	// Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
	register_nav_menus(array(
			'primary_navigation' => __('Primary Navigation', 'studiofolio'),
		));

	// Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
	add_theme_support('post-thumbnails');
	add_image_size( 'x-small', 100, 9999 );
	add_image_size( 'small', 200, 9999 );
	update_option('medium_size_w', 410);
	update_option('medium_size_h', 9999);
	update_option('large_size_w', 830);
	update_option('large_size_h', 9999);
	add_image_size( 'x-large', 1250, 9999 );
	
	add_theme_support( 'automatic-feed-links' );
	
	// Add post formats (http://codex.wordpress.org/Post_Formats)
	add_theme_support('post-formats', array('quote', 'video', 'audio','image'));

	// Tell the TinyMCE editor to use a custom stylesheet
	add_editor_style('assets/css/editor-style.css');

}

add_action('after_setup_theme', 'studiofolio_setup');


if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }