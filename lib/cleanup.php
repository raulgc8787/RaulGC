<?php

/**
 * Clean up wp_head()
 *
 * Remove unnecessary <link>'s
 * Remove inline CSS used by Recent Comments widget
 * Remove inline CSS used by posts with galleries
 * Remove self-closing tag and change ''s to "'s on rel_canonical()
 */
function studiofolio_head_cleanup() {
  // Originally from http://wpengineer.com/1438/wordpress-header/
  remove_action('wp_head', 'feed_links', 2);
  remove_action('wp_head', 'feed_links_extra', 3);
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

  global $wp_widget_factory;
  remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));

  if (!class_exists('WPSEO_Frontend')) {
    remove_action('wp_head', 'rel_canonical');
    add_action('wp_head', 'studiofolio_rel_canonical');
  }
}

function studiofolio_rel_canonical() {
  global $wp_the_query;

  if (!is_singular()) {
    return;
  }

  if (!$id = $wp_the_query->get_queried_object_id()) {
    return;
  }

  $link = get_permalink($id);
  echo "\t<link rel=\"canonical\" href=\"$link\">\n";
}

add_action('init', 'studiofolio_head_cleanup');

function add_custom_styles() {
	global $data;
	$background = get_post_meta(get_queried_object_id(), '_studiofolio_all_meta', true);
	$containerstyle = '';
	$custom_css = (isset($data['custom_css']) && $data['custom_css']) ? $data['custom_css'] : ''; 

	if ($background && array_key_exists('bgurl',$background)) {
		if (isset($background['cover']) && $background['cover']) $containerstyle = '#background {background: url('.$background['bgurl'].')  no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;}';
		else {
			$hor = (isset($background['alignh']) && $background['alignh']) ? $background['alignh'] : 'center';
			$ver = (isset($background['alignv']) && $background['alignv']) ? $background['alignv'] : 'center';
			$rep = (isset($background['repeat']) && $background['repeat']) ? $background['repeat'] : 'repeat';
			$containerstyle = '#background {background: url('.$background['bgurl'].')  '.$rep.' '.$hor.' '.$ver.';}';
		}
	}
	$bodystyle = ($background && array_key_exists('bgcolor',$background)) ? 'body {background-color: '.$background['bgcolor'].'}' : ''; 

	echo "\t<style type=\"text/css\">\n";
	echo $bodystyle;
	if (isset($data['full_width']) && !$data['full_width'] && isset($data['cont_width']) && $data['cont_width'] != '') {
		$width = intval($data['cont_width']);
		$fixwidth = $width - 40 ;
		echo "\t\tbody.fixed-width { padding: 0 20px; }\n";
		echo "\t\tbody.fixed-width #inner-menu { max-width: {$width}px; }\n";
		echo "\t\tbody.fixed-width #content { max-width: {$width}px; }\n";
		echo "\t\tbody.fixed-width .fixed-wrap { max-width: {$width}px; margin: 0 auto; position: relative; }\n";
	}
	echo $containerstyle;
	echo $custom_css;
	echo "</style>";
}

add_action('wp_head', 'add_custom_styles');

function add_custom_scripts() {
	global $data;
	
	$speed = (isset($data['speed_load']) && $data['speed_load']) ? $data['speed_load'] : 200;
	
	$custom_jscript = (isset($data['custom_jscript']) && $data['custom_jscript']) ? $data['custom_jscript'] : ''; 

	echo "\n\t<script type=\"text/javascript\">\n";
	echo "\t\tvar speedLoad = {$speed};\n";
	echo "\t\tvar siteUrl = '".get_template_directory_uri()."';\n";
	echo "\t\t".$custom_jscript."\n";
	echo "\t</script>\n";
}

add_action('wp_head', 'add_custom_scripts');

/**
 * Remove the WordPress version from RSS feeds
 */
add_filter('the_generator', '__return_false');

/**
 * Clean up language_attributes() used in <html> tag
 *
 * Change lang="en-US" to lang="en"
 * Remove dir="ltr"
 */
function studiofolio_language_attributes() {
  $attributes = array();
  $output = '';

  if (function_exists('is_rtl')) {
    if (is_rtl() == 'rtl') {
      $attributes[] = 'dir="rtl"';
    }
  }

  $lang = get_bloginfo('language');

  if ($lang && $lang !== 'en-US') {
    $attributes[] = "lang=\"$lang\"";
  } else {
    $attributes[] = 'lang="en"';
  }

  $output = implode(' ', $attributes);
  $output = apply_filters('studiofolio_language_attributes', $output);

  return $output;
}

add_filter('language_attributes', 'studiofolio_language_attributes');

/**
 * Clean up output of stylesheet <link> tags
 */
function studiofolio_clean_style_tag($input) {
  preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
  // Only display media if it's print
  $media = $matches[3][0] === 'print' ? ' media="print"' : '';
  return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}

add_filter('style_loader_tag', 'studiofolio_clean_style_tag');

/**
 * Add and remove body_class() classes
 */
function studiofolio_body_class($classes) {
  global $data, $post;
  // Add 'top-navbar' class if using Bootstrap's Navbar
  // Used to add styling to account for the WordPress admin bar
  if (current_theme_supports('bootstrap-top-navbar')) {
    $classes[] = 'top-navbar';
  }

  // Add post/page slug
  if (is_single() || is_page() && !is_front_page()) {
    $classes[] = basename(get_permalink());
  }
  
  // Remove unnecessary classes
  $home_id_class = 'page-id-' . get_option('page_on_front');
  $remove_classes = array(
    'page-template-default',
    $home_id_class
  );
  $classes = array_diff($classes, $remove_classes);
  
  if (isset($data['alt_stylesheet']) && $data['alt_stylesheet']) {
  	if ($data['alt_stylesheet'] == 'light.css') $style = 'light-css'; 
  	else $style = 'dark-css'; 
  } else $style = 'light-css'; 
  
  $classes[] = $style;
  
  if (isset($data['left_menu']) && $data['left_menu']) $classes[] = 'left-menu';
  
  if (isset($data['hover_menu']) && $data['hover_menu']) $classes[] = 'hover-menu';
  
  if (isset($data['infinite_scroll']) && $data['infinite_scroll']) $classes[] = 'infinite-scroll';
  
  if (isset($data['full_width']) && $data['full_width']) $classes[] = 'full-width';
  else $classes[] = 'fixed-width';
  
  if (isset($data['fix_menu']) && $data['fix_menu']) {
  	$classes[] = 'fixed-menu';
  	$classes[] = 'fixed-sidebar';
  }
  
  if (isset($data['block_gutter'])) {
  	if ($data['block_gutter'] == "0") $classes[] = 'two'; 
  	else $classes[] = 'twenty';
  } else $classes[] = 'two';
  
  $pagetemplate = '';

  if (!is_author() && !is_archive()) $pagetemplate = get_post_meta(get_queried_object_id(), '_post_template', true);

  switch ($pagetemplate) {
  	case ('templates/portfolio-sidebar-fixed.php'):
  		$classes[] = 'limit';
  	break;
  	case ('templates/portfolio-sidebar-iso.php'):
  	break;
  	case ('templates/portfolio-sidebar-iso-fixed.php'):
  		$classes[] = 'limit';
  	break;
  	case ('templates/portfolio-center.php'):
  		if (isset($data['full_width']) && $data['full_width']) $classes[] = 'limit';
  		$classes[] = 'portfolio-center';
  	break;
  	case ('templates/portfolio-center-iso-fixed.php'):
  		if (isset($data['full_width']) && $data['full_width']) $classes[] = 'limit';
  		$classes[] = 'horizontal';
  	break;
  	case ('templates/blog-center.php'):
  		if (isset($data['full_width']) && $data['full_width']) $classes[] = 'limit';
  		$classes[] = 'blog-center';
  	break;
  	case ('templates/blog-center-featured.php'):
  		if (isset($data['full_width']) && $data['full_width']) $classes[] = 'limit';
  		$classes[] = 'blog-center-featured';
  	break;
  	case ('templates/blog-side.php'):
  		$classes[] = 'limit';
  		$classes[] = 'blog-side';
  	break;
  	case ('templates/blog-full.php'):
  		$classes[] = 'blog-full';
  	break;
  	case ('templates/gallery.php'):
  		$classes[] = 'gallery-full';
  	break;
  }

  if (isset($data['regular_index']) && $data['regular_index'] && (is_home() || is_archive() || is_search()) && get_post_type() != 'portfolio') $classes[] = 'limit';
  
  if (is_page_template('templates/contact-sidebar-fixed.php') || is_page_template('templates/contact-center.php') || is_page_template('templates/page-sidebar-fixed.php') || is_page_template('templates/page-center-iso-fixed.php') || is_page_template('templates/page-sidebar-iso-fixed.php') || is_page_template('templates/page-center.php') || is_page_template('templates/page-text-center.php') || is_page_template('templates/page-text-fixed.php')) if (isset($data['full_width']) && $data['full_width']) $classes[] = 'limit';
  
  if (is_page_template('templates/page-center.php')) $classes[] = 'portfolio-center';
  
  if (is_page_template('templates/gallery.php')) {
  	global $gallery_mb, $post;
  	$meta = get_post_meta($post->ID, $gallery_mb->get_the_id(), TRUE);
  	if (isset($meta['gallery_slidehow']) && $meta['gallery_slidehow']) $classes[] = 'gallery-full';
  }
  
  if (isset($data['slideshow_on']) && $data['slideshow_on'] && isset($data['slideshow']) && $data['slideshow'] && is_front_page()) $classes[] = 'gallery-full';
  
  if (isset($data['rs_style']) && $data['rs_style']) $classes[] = 'rs_undsgn';

  if (isset($data['deactive_overlay']) && $data['deactive_overlay']) $classes[] = 'deactive_overlay';

  if (isset($data['deactive_zoom']) && $data['deactive_zoom']) $classes[] = 'deactive_zoom';
  
  return $classes;
}

add_filter('body_class', 'studiofolio_body_class');


/**
 * Wrap embedded media as suggested by Readability
 *
 * @link https://gist.github.com/965956
 * @link http://www.readability.com/publishers/guidelines#publisher
 */
function studiofolio_embed_wrap($cache, $url, $attr = '', $post_ID = '') {
  if (false !== strpos($url, 'twitter.com')) {
      $cache = str_replace('width="550"','',$cache);
  }
  return '<div class="entry-content-asset">' . $cache . '</div>';
}

add_filter('embed_oembed_html', 'studiofolio_embed_wrap', 10, 4);
add_filter('embed_googlevideo', 'studiofolio_embed_wrap', 10, 2);

/**
 * Add class="thumbnail" to attachment items
 */
function studiofolio_attachment_link_class($html) {
  $postid = get_the_ID();
  $html = str_replace('<a', '<a class="thumbnail"', $html);
  return $html;
}

add_filter('wp_get_attachment_link', 'studiofolio_attachment_link_class', 10, 1);

/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function studiofolio_caption($output, $attr, $content) {
  if (is_feed()) {
    return $output;
  }

  $defaults = array(
    'id' => '',
    'align' => 'alignnone',
    'width' => '',
    'caption' => ''
  );

  $attr = shortcode_atts($defaults, $attr);

  // If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
  if (1 > $attr['width'] || empty($attr['caption'])) {
    return $content;
  }

  // Set up the attributes for the caption <figure>
  $attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
  $attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';
  $attributes .= ' style="width: ' . esc_attr($attr['width']) . 'px"';

  $output  = '<figure' . $attributes .'>';
  $output .= do_shortcode($content);
  $output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
  $output .= '</figure>';

  return $output;
}

add_filter('img_caption_shortcode', 'studiofolio_caption', 10, 3);

/**
 * Remove unnecessary dashboard widgets
 *
 * @link http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
 */
function studiofolio_remove_dashboard_widgets() {
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
  remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
  remove_meta_box('dashboard_primary', 'dashboard', 'normal');
  remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}

add_action('admin_init', 'studiofolio_remove_dashboard_widgets');

/**
 * Clean up the_excerpt()
 */
function studiofolio_excerpt_length($length) {
  return POST_EXCERPT_LENGTH;
}

function studiofolio_excerpt_more($more) {
  if ( has_excerpt() ) echo $more;
 // if (get_post_type() != 'post') return;
  global $data;
  
  if (isset($data['more_new_line']) && $data['more_new_line']) $newrow = true;
  else $newrow = false; 
  
  if (isset($data['more_text']) && $data['more_text']) $more_text = $data['more_text'];
  else $more_text = "Read More";
  
  if (!$newrow) $excerpt = '<a href="' . get_permalink() . '">' . $more_text . '</a>';
  else $excerpt = '<div class="entry-more-cont"><div class="entry-more"><a class="hvr" href="' . get_permalink() . '">' . $more_text . '</a></div></div>';
  
  return $excerpt; 
}

add_filter('excerpt_length', 'studiofolio_excerpt_length');
add_filter('the_excerpt', 'studiofolio_excerpt_more');
add_filter('the_content_more_link', 'studiofolio_excerpt_more');

/**
 * Replace various active menu class names with "active"
 */
function studiofolio_wp_nav_menu($text) {
  $text = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $text);
  $text = preg_replace('/( active){2,}/', ' active', $text);
  return $text;
}

add_filter('wp_nav_menu', 'studiofolio_wp_nav_menu');

/**
 * Cleaner walker for wp_nav_menu()
 *
 * Walker_Nav_Menu (WordPress default) example output:
 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * Studiofolio_Nav_Walker example output:
 *   <li class="menu-home"><a href="/">Home</a></li>
 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 */
class Studiofolio_Nav_Walker extends Walker_Nav_Menu {
  function check_current($classes) {
    return preg_match('/(current[-_])|active|dropdown/', $classes);
  }

  function start_lvl(&$output, $depth = 0, $args = array()) {
    $output .= "\n<ul class=\"dropdown-menu\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    global $wp_query;
    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $slug = sanitize_title($item->title);
    $id = 'menu-' . $slug;

    $class_names = $value = '';
    $li_attributes = '';
    $classes = empty($item->classes) ? array() : (array) $item->classes;

    $classes = array_filter($classes, array(&$this, 'check_current'));
    
    if ($args->has_children) {
      $classes[]      = 'dropdown';
      $li_attributes .= ' data-dropdown="dropdown"';
    }

    if ($custom_classes = get_post_meta($item->ID, '_menu_item_classes', true)) {
      foreach ($custom_classes as $custom_class) {
        $classes[] = $custom_class;
      }
    }

    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    if (get_post_type() == 'portfolio') {
    	$postmeta = get_post_meta(get_queried_object_id(), 'portfolio_page', true);
    	if ($item->object_id == $postmeta && isset($postmeta)) $addactive = ' active';
    	else {
    		if (!$args->has_children) $class_names = '';
    		$addactive = '';
    	}
    } else $addactive = '';
    
    $class_names = $class_names ? ' class="' . $id . ' ' . esc_attr($class_names) . $addactive . '"' : ' class="' . $id . $addactive . '"';

    $output .= $indent . '<li' . $class_names . '>';

    $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';
    $attributes .= ($args->has_children)      ? ' class="dropdown-toggle" data-toggle="dropdown" data-target="#"' : '';

    $item_output  = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= ($args->has_children) ? ' <b class="caret"></b>' : '';
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
    if (!$element) { return; }

    $id_field = $this->db_fields['id'];

    if (is_array($args[0])) {
      $args[0]['has_children'] = !empty($children_elements[$element->$id_field]);
    } elseif (is_object($args[0])) {
      $args[0]->has_children = !empty($children_elements[$element->$id_field]);
    }

    $cb_args = array_merge(array(&$output, $element, $depth), $args);
    call_user_func_array(array(&$this, 'start_el'), $cb_args);

    $id = $element->$id_field;

    if (($max_depth == 0 || $max_depth > $depth+1) && isset($children_elements[$id])) {
      foreach ($children_elements[$id] as $child) {
        if (!isset($newlevel)) {
          $newlevel = true;
          $cb_args = array_merge(array(&$output, $depth), $args);
          call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
        }
        $this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $output);
      }
      unset($children_elements[$id]);
    }

    if (isset($newlevel) && $newlevel) {
      $cb_args = array_merge(array(&$output, $depth), $args);
      call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
    }

    $cb_args = array_merge(array(&$output, $element, $depth), $args);
    call_user_func_array(array(&$this, 'end_el'), $cb_args);
  }
}

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Use Studiofolio_Nav_Walker() by default
 */
function studiofolio_nav_menu_args($args = '') {
  $studiofolio_nav_menu_args['container'] = false;

  if (!$args['items_wrap']) {
    $studiofolio_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
  }

  // Bootstrap's navbar doesn't support multi-level dropdowns
  if (current_theme_supports('bootstrap-top-navbar')) {
    $studiofolio_nav_menu_args['depth'] = 2;
  }

  if (!$args['walker']) {
    $studiofolio_nav_menu_args['walker'] = new Studiofolio_Nav_Walker();
  }

  return array_merge($args, $studiofolio_nav_menu_args);
}

add_filter('wp_nav_menu_args', 'studiofolio_nav_menu_args');

/**
 * Remove unnecessary self-closing tags
 */
function studiofolio_remove_self_closing_tags($input) {
  return str_replace(' />', '>', $input);
}

add_filter('get_avatar',          'studiofolio_remove_self_closing_tags'); // <img />
add_filter('comment_id_fields',   'studiofolio_remove_self_closing_tags'); // <input />
add_filter('post_thumbnail_html', 'studiofolio_remove_self_closing_tags'); // <img />

/**
 * Don't return the default description in the RSS feed if it hasn't been changed
 */
function studiofolio_remove_default_description($bloginfo) {
  $default_tagline = 'Just another WordPress site';

  return ($bloginfo === $default_tagline) ? '' : $bloginfo;
}

add_filter('get_bloginfo_rss', 'studiofolio_remove_default_description');

/**
 * Allow more tags in TinyMCE including <iframe> and <script>
 */
function studiofolio_change_mce_options($options) {
  $ext = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src],script[charset|defer|language|src|type]';

  if (isset($initArray['extended_valid_elements'])) {
    $options['extended_valid_elements'] .= ',' . $ext;
  } else {
    $options['extended_valid_elements'] = $ext;
  }

  return $options;
}

add_filter('tiny_mce_before_init', 'studiofolio_change_mce_options');

/**
 * Add additional classes onto widgets
 *
 * @link http://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets
 */
function studiofolio_widget_first_last_classes($params) {
  global $my_widget_num;

  $this_id = $params[0]['id'];
  $arr_registered_widgets = wp_get_sidebars_widgets();

  if (!$my_widget_num) {
    $my_widget_num = array();
  }

  if (!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) {
    return $params;
  }

  if (isset($my_widget_num[$this_id])) {
    $my_widget_num[$this_id] ++;
  } else {
    $my_widget_num[$this_id] = 1;
  }

  $class = 'class="widget-' . $my_widget_num[$this_id] . ' ';

  if ($my_widget_num[$this_id] == 1) {
    $class .= 'widget-first ';
  } elseif ($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) {
    $class .= 'widget-last ';
  }

  $params[0]['before_widget'] = preg_replace('/class=\"/', "$class", $params[0]['before_widget'], 1);

  return $params;
}

add_filter('dynamic_sidebar_params', 'studiofolio_widget_first_last_classes');

/**
 * Redirects search results from /?s=query to /search/query/, converts %20 to +
 *
 * @link http://txfx.net/wordpress-plugins/nice-search/
 */
function studiofolio_nice_search_redirect() {
  if (is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/search/') === false) {
    wp_redirect(home_url('/search/' . str_replace(array(' ', '%20'), array('+', '+'), urlencode(get_query_var('s')))), 301);
    exit();
  }
}

add_action('template_redirect', 'studiofolio_nice_search_redirect');

/**
 * Fix for get_search_query() returning +'s between search terms
 */
function studiofolio_search_query($escaped = true) {
  $query = apply_filters('studiofolio_search_query', get_query_var('s'));

  if ($escaped) {
    $query = esc_attr($query);
  }

  return urldecode($query);
}

add_filter('get_search_query', 'studiofolio_search_query');

/**
 * Fix for empty search queries redirecting to home page
 *
 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
 * @link http://core.trac.wordpress.org/ticket/11330
 */
function studiofolio_request_filter($query_vars) {
  if (isset($_GET['s']) && empty($_GET['s'])) {
    $query_vars['s'] = ' ';
  }

  return $query_vars;
}

add_filter('request', 'studiofolio_request_filter');

/**
 * Tell WordPress to use searchform.php from the templates/ directory
 */
function studiofolio_get_search_form($form) {
  $form = '';
  locate_template('templates/searchform.php', true, true);

  return $form;
}

add_filter('get_search_form', 'studiofolio_get_search_form');

add_filter( 'widget_tag_cloud_args', 'my_widget_tag_cloud_args' );
function my_widget_tag_cloud_args( $args ) {
	// Your extra arguments go here
	$args['largest'] = 13;
	$args['smallest'] = 13;
	$args['unit'] = 'px';
	$args['format'] = 'list';
	return $args;
}
