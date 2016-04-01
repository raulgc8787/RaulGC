<?php

/**
 * Add the RSS feed link in the <head> if there's posts
 */
function studiofolio_feed_link() {
  $count = wp_count_posts('post'); if ($count->publish > 0) {
    echo "\n\t<link rel=\"alternate\" type=\"application/rss+xml\" title=\"". get_bloginfo('name') ." Feed\" href=\"". home_url() ."/feed/\">\n";
  }
}

add_action('wp_head', 'studiofolio_feed_link', -2);

/**
 * Add the asynchronous Google Analytics snippet from HTML5 Boilerplate
 * if an ID is defined in config.php
 *
 * @link mathiasbynens.be/notes/async-analytics-snippet
 */
function studiofolio_google_analytics() {
  if (GOOGLE_ANALYTICS_ID) {
    $input = get_site_url();
    $input = trim($input, '/');

    // If scheme not included, prepend it
    if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
    }

    // remove www
    $domain = preg_replace('/^www\./', '', $input);
    $domain = preg_replace('#^http(s)?://#', '', $input);

    echo "\n\t<script>\n";
    echo "\t\t(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\n";
    echo "\t\t(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n";
    echo "\t\tm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n";
    echo "\t\t})(window,document,'script','//www.google-analytics.com/analytics.js','ga');\n";
    echo "\t\tga('create', '".GOOGLE_ANALYTICS_ID."', '".$domain."');\n";
    echo "\t\tga('send', 'pageview');\n";
    echo "\t</script>\n";
  }
  echo "\n\t<script type='text/javascript' src='".get_template_directory_uri()."/assets/js/vendor/retina.js'>\n";
  echo "\t</script>\n";
}

add_action('wp_footer', 'studiofolio_google_analytics');

function studiofolio_google_maps() {
	global $contact_mb;
  if ($contact_mb->get_the_value('asw') && $contact_mb->get_the_value('ash') && $contact_mb->get_the_value('lat') && $contact_mb->get_the_value('lon')) {
  	$w = $contact_mb->get_the_value('asw');
  	$h = $contact_mb->get_the_value('ash');
  	$la = $contact_mb->get_the_value('lat');
  	$lo = $contact_mb->get_the_value('lon');
  	echo "\n\t<script>\n";
  	echo "\n\tif (document.getElementById('map')) {\n";
  	echo "\t\t/*  gMap\n";
    	echo "\t\t========================================================================== */\n";
	echo "\t\t// http://universimmedia.pagesperso-orange.fr/geo/loc.htm\n";
	echo "\t\tvar gmap = jQuery('#map');\n";
	echo "\t\tfunction setGMapHeight() {\n";
	echo "\t\tgmap.height((gmap.width() * $h) / $w);\n";
	echo "\t\t}\n";
	echo "\t\tsetGMapHeight();\n";
	echo "\t\tif (gmap.length) {\n";
	echo "\t\tgmap.gMap({\n";
	echo "\t\tcontrols: {\n";
	echo "\t\tpanControl: false,\n";
	echo "\t\tzoomControl: true,\n";
	echo "\t\tzoomControlOptions: {\n";
	echo "\t\tstyle: google.maps.ZoomControlStyle.SMALL\n";
	echo "\t\t},\n";
	echo "\t\tmapTypeControl: false,\n";
	echo "\t\tstreetViewControl: false,\n";
	echo "\t\toverviewMapControl: false\n";
	echo "\t\t},\n";
	echo "\t\tmaptype: 'ROADMAP',\n";
	echo "\t\tzoom: 14,\n";
	echo "\t\tmarkers: [{\n";
	echo "\t\tlatitude: $la,\n";
	echo "\t\tlongitude: $lo,\n";
	echo "\t\ticon: {\n";
	echo "\t\timage: '".get_template_directory_uri()."/assets/img/gmap_pin.png',\n";
	echo "\t\ticonsize: [31, 43],\n";
	echo "\t\ticonanchor: [15, 40]\n";
	echo "\t\t}\n";
	echo "\t\t}]\n";
	echo "\t\t});\n";
	echo "\t\t}\n";
	echo "\t\tjQuery(window).smartresize(function() {\n";
	echo "\t\tsetGMapHeight();\n";
	echo "\t\t}).resize();\n";
	echo "\t}\n";
	echo "\t</script>\n";
  }
 
}

add_action('wp_footer', 'studiofolio_google_maps');