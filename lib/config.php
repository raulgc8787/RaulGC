<?php
/**
 * Studiofolio configuration and constants
 */
 
global $data;

// Define which pages shouldn't have the sidebar
function studiofolio_sidebar() {
	global $post;
	if (!is_author()) $pagetemplate = get_post_meta(get_queried_object_id(), '_post_template', true);

  if ($pagetemplate == 'templates/portfolio-sidebar-iso-fixed.php' || $pagetemplate == 'templates/portfolio-sidebar-iso.php' || $pagetemplate == 'templates/portfolio-sidebar.php' || $pagetemplate == 'templates/portfolio-sidebar-fixed.php' || is_page_template('templates/page-sidebar.php') || is_page_template('templates/page-sidebar-fixed.php') || is_page_template('templates/page-sidebar-iso-fixed.php') || is_page_template('templates/page-sidebar-iso.php') || $pagetemplate == 'templates/blog-center.php' || $pagetemplate == 'templates/blog-side.php' || $pagetemplate == 'templates/blog-full.php' || is_page_template('templates/contact-sidebar.php') || is_page_template('templates/contact-sidebar-fixed.php') && (!is_home() || !is_front_page() || get_queried_object_id() != get_option('page_on_front'))  ) {
  	
    return true;
  } else {
    return false;
  }
}


// #main CSS classes
function studiofolio_main_class() {
  if (studiofolio_sidebar()) {
    echo 'span8';
  }
}

// #sidebar CSS classes
function studiofolio_sidebar_class() {
  echo 'span4';
}

$get_theme_name = explode('/themes/', get_template_directory());
define('LOGO_IMG', (isset($data['logoimg']) && $data['logoimg']) ? $data['logoimg'] : '' );
define('FAVICON', (isset($data['custom_favicon']) && $data['custom_favicon']) ? $data['custom_favicon'] : '' );
define('GOOGLE_ANALYTICS_ID',       (isset($data['google_analytics']) && $data['google_analytics']) ? $data['google_analytics'] : '' ); // UA-XXXXX-Y
define('POST_EXCERPT_LENGTH',       40);
define('WP_BASE',                   wp_base_dir());
define('THEME_NAME',                next($get_theme_name));
define('RELATIVE_PLUGIN_PATH',      str_replace(site_url() . '/', '', plugins_url()));
define('FULL_RELATIVE_PLUGIN_PATH', WP_BASE . '/' . RELATIVE_PLUGIN_PATH);
define('RELATIVE_CONTENT_PATH',     str_replace(site_url() . '/', '', content_url()));
define('THEME_PATH',                RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);

// Set the content width based on the theme's design and stylesheet
if (!isset($content_width)) { $content_width = 940; }
