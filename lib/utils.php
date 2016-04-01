<?php

/**
 * Theme Wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

function studiofolio_template_path() {
	return Studiofolio_Wrapping::$main_template;
}

class Studiofolio_Wrapping {

	// Stores the full path to the main template file
	static $main_template;

	// Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	static $base;

	static function wrap($template) {
		self::$main_template = $template;

		self::$base = substr(basename(self::$main_template), 0, -4);

		if ('index' == self::$base) {
			self::$base = false;
		}

		$templates = array('base.php');

		if (self::$base) {
			array_unshift($templates, sprintf('base-%s.php', self::$base ));
		}

		return locate_template($templates);
	}
}

add_filter('template_include', array('Studiofolio_Wrapping', 'wrap'), 99);

// returns WordPress subdirectory if applicable
function wp_base_dir() {
	preg_match('!(https?://[^/|"]+)([^"]+)?!', site_url(), $matches);
	if (count($matches) === 3) {
		return end($matches);
	} else {
		return '';
	}
}

// opposite of built in WP functions for trailing slashes
function leadingslashit($string) {
	return '/' . unleadingslashit($string);
}

function unleadingslashit($string) {
	return ltrim($string, '/');
}

function add_filters($tags, $function) {
	foreach($tags as $tag) {
		add_filter($tag, $function);
	}
}

function get_image_size($size) {
	switch ($size) {
		case ('width1'):
			$imgsize = 'small';
			break;
		case ('width2'):
			$imgsize = 'medium';
			break;
		case ('width4'):
			$imgsize = 'large';
			break;
		case ('width6'):
			$imgsize = 'x-large';
			break;
		default:
			$imgsize = 'medium';
			break;
	}
	return $imgsize;
}

function get_dummy_height($width, $height) {
	return (($height / $width) * 100) - .5;
}

/**
 * Conditional function to check if post belongs to term in a custom taxonomy.
 *
 * @param    tax        string                taxonomy to which the term belons
 * @param    term    int|string|array    attributes of shortcode
 * @param    _post    int                    post id to be checked
 * @return             BOOL                True if term is matched, false otherwise
 */
function pa_in_taxonomy($tax, $term, $_post = NULL) {
	// if neither tax nor term are specified, return false
	if ( !$tax || !$term ) { return FALSE; }
	// if post parameter is given, get it, otherwise use $GLOBALS to get post
	if ( $_post ) {
		$_post = get_post( $_post );
	} else {
		$_post =& $GLOBALS['post'];
	}
	// if no post return false
	if ( !$_post ) { return FALSE; }
	// check whether post matches term belongin to tax
	$return = is_object_in_term( $_post->ID, $tax, $term );
	// if error returned, then return false
	if ( is_wp_error( $return ) ) { return FALSE; }
	return $return;
}

function find_oembed($string) {
	$sites = array(
		array('#https?://(www\.)?youtube.com/watch.*#i'			   , 'Youtube'),
		array('/youtu.be\/[a-z1-9.-_]+/'                           , 'Youtube'),
		array('/blip.tv\/[a-z1-9.-_]+/'                            , 'Blip_Tv'),
		array('#https?://(www\.)?vimeo\.com/.*#i'                  , 'Vimeo'),
		array('#https?://(www\.)?dailymotion\.com/.*#i'            , 'Dailymotion'),
		array('#https?://(www\.)?flickr\.com/.*#i'                 , 'Flickr'),
		array('#https?://(www\.)?flic\.kr/.*#i'   	               , 'Flickr'),
		array('#https?://(.+\.)?smugmug\.com/.*#i'                 , 'Smugmug'),
		array('#https?://(www\.)?hulu\.com/watch/.*#i'             , 'Hulu'),
		array('#https?://(www\.)?viddler\.com/.*#i'                , 'Viddler'),
		array('/qik.com\/[a-z1-9.-_]+/'                            , 'Qik'),
		array('/revision3.com\/[a-z1-9.-_]+/'                      , 'Revision3'),
		array('/photobucket.com\/[a-z1-9.-_]+/'                    , 'Photobucket'),
		array('#https?://(www\.)?scribd\.com/.*#i'                 , 'Scribd'),
		array('/wordpress.tv\/[a-z1-9.-_]+/'                       , 'Wordpress_TV'),
		array('#https?://(.+\.)?polldaddy\.com/.*#i'               , 'Polldaddy'),
		array('#https?://(www\.)?funnyordie\.com/videos/.*#i'      , 'Funnyordie'),
		array('#https?://(www\.)?twitter.com/.+?/status(es)?/.*#i' , 'Twitter'),
		array('#https?://(www\.)?soundcloud\.com/.*#i'             , 'Soundcloud'),
		array('#https?://(www\.)?slideshare.net/*#'                , 'Slideshare'),
		array('#http://instagr(\.am|am\.com)/p/.*#i'               , 'Instagram'),
		array('/.swf/i'										   	   , 'Flash_file'),
		array('/\[(.*?)\](.*?)\[\/(.*?)\]/i'					   , 'Shortcode'),
		array('/\/iframe/i'				  						   , 'Iframe'),
	);
	
	foreach ($sites as $site) {
		if (preg_match($site[0], $string, $matches)) $provider = $site[1];
	}
	
	if (!isset($provider)) {
		$pattern = get_shortcode_regex();
		preg_match('/'.$pattern.'/s', $string, $matches);
		if (!empty($matches)) $provider = $matches[2];
	}
	
	if (!isset($provider)) $provider = 'File might not be supported';

	return $provider;
	
}
