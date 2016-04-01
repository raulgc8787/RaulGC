<?php
/**
 * Scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/bootstrap.css
 * 2. /theme/assets/css/bootstrap-responsive.css
 * 3. /theme/assets/css/app.css
 * 4. /child-theme/style.css (if a child theme is activated)
 *
 * Enqueue scripts in the following order:
 * 1. /theme/assets/js/vendor/modernizr-2.6.2.min.js  (in head.php)
 * 2. jquery-1.8.0.min.js via Google CDN              (in head.php)
 * 3. /theme/assets/js/plugins.js
 * 4. /theme/assets/js/main.js
 */

function studiofolio_scripts() {
  global $data;
  wp_enqueue_style('studiofolio_bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', false, null);
  wp_enqueue_style('studiofolio_bootstrap_responsive', get_template_directory_uri() . '/assets/css/responsive.css', array('studiofolio_bootstrap'), null);
  $stylesheet = (isset($data['alt_stylesheet']) && $data['alt_stylesheet']) ? $data['alt_stylesheet'] : 'light.css';
  wp_enqueue_style('studiofolio_app', get_template_directory_uri() . '/assets/css/'.$stylesheet, false, null);
  wp_enqueue_style('fresco', get_template_directory_uri() . '/assets/css/fresco.css', false, null);
  wp_register_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:300,400,700');
  wp_enqueue_style( 'googleFonts');

  // Load style.css from child theme
  if (is_child_theme()) {
    wp_enqueue_style('studiofolio_child', get_stylesheet_uri(), false, null);
  }

  // jQuery is loaded in header.php using the same method from HTML5 Boilerplate:
  // Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
  // It's kept in the header instead of footer to avoid conflicts with plugins.
  if (is_admin()) {
    wp_deregister_script('jquery');
    //wp_register_script('jquery', get_template_directory_uri() . '/assets/js/vendor/jquery-1.8.0.min.js', false, null, false);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('studiofolio_modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.6.2.min.js', false, null, false);
  wp_register_script('studiofolio_plugins', get_template_directory_uri() . '/assets/js/plugins.js', false, null, false);
  wp_register_script('studiofolio_main', get_template_directory_uri() . '/assets/js/main.js', false, null, false);
  wp_enqueue_script("jquery");
  wp_enqueue_script('studiofolio_modernizr');
  wp_enqueue_script('studiofolio_plugins');
  wp_enqueue_script('studiofolio_main');
}

add_action('wp_enqueue_scripts', 'studiofolio_scripts', 100);
