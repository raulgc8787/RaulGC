<?php

add_action('admin_init','undsgn_options');

if (!function_exists('undsgn_options')) {
	function undsgn_options(){

		//Access the WordPress Categories via an Array
		$undsgn_categories = array();
		$undsgn_categories_obj = get_categories('hide_empty=0');
		foreach ($undsgn_categories_obj as $undsgn_cat) {
			$undsgn_categories[$undsgn_cat->cat_ID] = $undsgn_cat->cat_name;}
		$categories_tmp = array_unshift($undsgn_categories, "Select a category:");

		//Access the WordPress Pages via an Array
		$undsgn_pages = array();
		$undsgn_pages_obj = get_pages('sort_column=post_parent,menu_order');
		foreach ($undsgn_pages_obj as $undsgn_page) {
			$undsgn_pages[$undsgn_page->ID] = $undsgn_page->post_name; }
		$undsgn_pages_tmp = array_unshift($undsgn_pages, "Select a page:");


		$undsgn_options_num_columns = array("2" => "Two","3" => "Three","4" => "Four","6" => "Six");
		$undsgn_options_homepage_blocks = array(
			"disabled" => array (
				"placebo"   => "placebo", //REQUIRED!
				"block_one"  => "Block One",
				"block_two"  => "Block Two",
				"block_three" => "Block Three",
			),
			"enabled" => array (
				"placebo" => "placebo", //REQUIRED!
				"block_four" => "Block Four",
			),
		);


		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array("light.css" => "Light","dark.css" => "Dark");

		$undsgn_options_num_columns = array("2" => "Two","3" => "Three","4" => "Four","6" => "Six");

		$undsgn_options_slideshow_effects = array("fadeeff" => "Fade","slideeff" => "Slide");

		//Background Images Reader
		$bg_images_path = get_stylesheet_directory() . '/libs/img/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri() .'/libs/img/bg/'; // change this to where you store your bg images
		$bg_images = array();

		if ( is_dir($bg_images_path) ) {
			if ($bg_images_dir = opendir($bg_images_path) ) {
				while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
					if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
						$bg_images[] = $bg_images_url . $bg_images_file;
					}
				}
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/

		//More Options
		$uploads_arr = wp_upload_dir();
		$all_uploads_path = $uploads_arr['path'];
		$all_uploads = get_option('undsgn_uploads');
		$block_gutter = array("2","20");
		
		global $wpdb;
		
		$querydetails = "
		   SELECT wposts.*
		   FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
		   WHERE wposts.ID = wpostmeta.post_id
		   AND wpostmeta.meta_key = '_wp_page_template'
		   AND wpostmeta.meta_value = 'templates/gallery.php'
		   AND wposts.post_status = 'publish'
		   AND wposts.post_type = 'page'
		   ORDER BY wposts.post_date ASC
		 ";
		 
		 $pageposts = $wpdb->get_results($querydetails, OBJECT);
		 
		 $galleries[0] = 'Not selected...';
		 if ($pageposts):
		 	foreach ($pageposts as $post):
		 		$galleries[$post->ID] = $post->post_title;
		 	endforeach;
		 endif;

		$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");

		// Image Alignment radio box
		$undsgn_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center");

		// Image Links to Options
		$undsgn_options_image_link_to = array("image" => "The Image","post" => "The Post");
		
		if (is_plugin_active('revslider/revslider.php')) {
		  global $wpdb;
		  $rs = $wpdb->get_results( 
		  	"
		  	SELECT id, title, alias
		  	FROM ".$wpdb->prefix."revslider_sliders
		  	ORDER BY id ASC LIMIT 100
		  	"
		  );
		  $revsliders = array();
		  if ($rs) {
		    foreach ( $rs as $slider ) {
		      $revsliders[$slider->title] = $slider->alias;
		    }
		  } else {
		    $revsliders["No sliders found"] = 0;
		  }
		} // if revslider plugin active


		/*-----------------------------------------------------------------------------------*/
		/* The Options Array */
		/*-----------------------------------------------------------------------------------*/

		// Set the Options Array
		global $undsgn_options;
		$undsgn_options = array();

		$undsgn_options[] = array( "name" => "General Settings",
			"type" => "heading");

		$undsgn_options[] = array( "name" => "Logo",
			"desc" => "Upload your logo for regular display here Png/Gif/Jpg.",
			"id" => "logoimg",
			"std" => "",
			"mod" => "min",
			"type" => "media");

		$undsgn_options[] = array( "name" => "Logo (retina)",
			"desc" => "Upload your logo for retina display.<br />It has have the same name and doubled the sized then the regular. The name also need to contain the chars @2x at the end. Ex. 'mylogo.png' becomes 'mylogo@2x.png'",
			"id" => "logoimg2x",
			"std" => "",
			"mod" => "min",
			"type" => "media");

		$undsgn_options[] = array( "name" => "Theme Stylesheet",
			"desc" => "Select your themes alternative color scheme.",
			"id" => "alt_stylesheet",
			"std" => "light.css",
			"type" => "select2",
			"options" => $alt_stylesheets);

		$undsgn_options[] = array( "name" => "Infinite scroll",
			"desc" => "Activate the infinite scroll throughout the all site.",
			"id" => "infinite_scroll",
			"std" => "0",
			"type" => "checkbox");
		
		if (is_plugin_active('revslider/revslider.php')) {
				
			$undsgn_options[] = array( "name" => "Slider Revolution style",
				"desc" => "Apply the Undsgn style to the Slider Revolution",
				"id" => "rs_style",
				"std" => "0",
				"type" => "checkbox");
		}

		$undsgn_options[] = array( "name" => "Deactivate overlay block effect",
			"desc" => "On mouse over isotope block don't show the overlay layer.",
			"id" => "deactive_overlay",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "Deactivate zoom block effect",
			"desc" => "On mouse over isotope block don't show the zoom effect.",
			"id" => "deactive_zoom",
			"std" => "0",
			"type" => "checkbox");
			
		$undsgn_options[] = array( "name" => "Menu options",
			"desc" => "Open the submenu with mouse hover.",
			"id" => "hover_menu",
			"std" => "0",
			"type" => "checkbox");
			
		$undsgn_options[] = array( "name" => "",
			"desc" => "Vertical menu on the left side.",
			"id" => "left_menu",
			"std" => "0",
			"folds" => "1",
			"type" => "checkbox");
			
		$undsgn_options[] = array( "name" => "",
			"desc" => "Optional message under the menu.",
			"id" => "vertical_message",
			"std" => "0",
			"fold" => "left_menu",
			"type" => "textarea");
			
		$undsgn_options[] = array( "name" => "",
			"desc" => "Horizontal menu and positioned next to the logo (otherwise it will create a new line).<br><br>NOTE: In case of chosing the horizontal menu we suggest to apply the hack descripted here <a href='http://www.undsgn.com/support/discussion/594#Item_15' target='_blank'>http://www.undsgn.com/support/discussion/594#Item_15</a> by adding custom CSS inside our Custom Style panel.",
			"id" => "inline_menu",
			"std" => "0",
			"unfold" => "left_menu",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "Fixed menu & sidebar",
			"desc" => "Activate this if you want the menu to follow when the page is scrolled.",
			"id" => "fix_menu",
			"std" => "0",
			"unfold" => "left_menu",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "Site width",
			"desc" => "Full width",
			"id" => "full_width",
			"std" => "1",
			"folds" => "1",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "Width container",
			"desc" => "Enter the width for your main container. Default: 1000px.",
			"id" => "cont_width",
			"unfold" => "full_width",
			"std" => "1000",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Blocks gutter",
			"desc" => "Space between blocks in pixels",
			"id" => "block_gutter",
			"std" => "0",
			"type" => "select2",
			"options"  => $block_gutter);

		$undsgn_options[] = array( "name" => "Custom Favicon",
			"desc" => "Upload a 16px x 16px Png/Gif image that will represent your website's favicon.",
			"id" => "custom_favicon",
			"std" => "",
			"mod" => "min",
			"type" => "upload");
			
		$undsgn_options[] = array( "name" => "Classic blog index with sidebar",
			"desc" => "Activate the classic blog index page",
			"id" => "regular_index",
			"std" => "0",
			"type" => "checkbox");


		$undsgn_options[] = array( "name" => "Custom Read More",
			"desc" => "New line",
			"id" => "more_new_line",
			"std" => "1",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "HTML code",
			"id" => "more_text",
			"std" => "Read More",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Elements fading animation speed",
			"desc" => "Enter the fading speed of all the elements in milliseconds. Default 200",
			"id" => "speed_load",
			"std" => "200",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Tracking Code",
			"desc" => "Paste your Google Analytics ID here. Ex. UA-XXXXXXXX-XX",
			"id" => "google_analytics",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Site redirect if browser is IE 6 or minor",
			"desc" => "If the users browser is Internet Explorer 6 or minor it will be redirect to this address.<br />Default: http://browsehappy.com/",
			"id" => "IERedirect",
			"std" => "http://browsehappy.com/",
			"type" => "text");
		
		$undsgn_options[] = array( "name" => "Social Options",
			"type" => "heading");

		$undsgn_options[] = array( "name" => "Twitter",
			"desc" => "Twitter URL",
			"id" => "twitter_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Linkedin",
			"desc" => "Linkedin URL",
			"id" => "in_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Facebook",
			"desc" => "Facebook URL",
			"id" => "facebook_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Google+",
			"desc" => "Google+ URL",
			"id" => "google_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Pinterest",
			"desc" => "Pinterest URL",
			"id" => "pinterest_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Github",
			"desc" => "Github URL",
			"id" => "github_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Flicker",
			"desc" => "Flicker",
			"id" => "flicker_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Tumblr",
			"desc" => "Tumblr URL",
			"id" => "tumblr_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Dribbble",
			"desc" => "Dribbble URL",
			"id" => "dribbble_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Soundcloud",
			"desc" => "Soundcloud URL",
			"id" => "soundcloud_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Last.fm",
			"desc" => "Last.fm URL",
			"id" => "lastfm_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Behance",
			"desc" => "Behance URL",
			"id" => "behance_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Instagram",
			"desc" => "Instagram URL",
			"id" => "instagram_url",
			"std" => "",
			"type" => "text");

		$undsgn_options[] = array( "name" => "Vimeo",
			"desc" => "Vimeo URL",
			"id" => "vimeo_url",
			"std" => "",
			"type" => "text");
		
		$querystr = "
	    SELECT $wpdb->posts.* 
	    FROM $wpdb->posts, $wpdb->postmeta
	    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
	    AND $wpdb->postmeta.meta_key = '_wp_page_template' 
	    AND $wpdb->postmeta.meta_value = 'templates/front-page.php' 
	    AND $wpdb->posts.post_status = 'publish' 
	    AND $wpdb->posts.post_type = 'page'";
	
		$pageposts = $wpdb->get_results($querystr, OBJECT);
		
		$i = 0;
		foreach ($pageposts as $pagepost){
			$addId = ($i == 0) ? '' : '_' . $pagepost->ID;
			$undsgn_options[] = array( "name" => ucwords($pagepost->post_name).' - Page',
				"type" => "heading");
	
			$undsgn_options[] = array( "name" => "HTML text in the feature area",
				"desc" => "This will be shown in the box after the menu.",
				"id" => "html_text_feature".$addId,
				"type" => "textarea");
	
			$undsgn_options[] = array( "name" => "Slideshow On",
				"desc" => "Switch on the featured slideshow.",
				"id" => "slideshow_on".$addId,
				"std" => "0",
				"folds" => "1",
				"type" => "checkbox");
		
			if (is_plugin_active('revslider/revslider.php')) {
			
				$undsgn_options[] = array( "name" => "Which slider?",
					"desc" => "Activate Slider Revolution if not Flexslider.",
					"id" => "rs_on".$addId,
					"std" => "0",
					"folds" => "1",
					"fold" => 'slideshow_on'.$addId,
					"type" => "checkbox"
				);
				
				$undsgn_options[] = array( "name" => "Slider Revolution",
					"desc" => "Choose the slider revolutions slide",
					"id" => "rs_id".$addId,
					"std" => "0",
					"fold" => 'rs_on'.$addId,
					"type" => "select2",
					"options"  => $revsliders
				);
				
			}
	
			$undsgn_options[] = array( "name" => "Flexslider slideshow",
				"desc" => "Slideshow from gallery page",
				"id" => "slideshow".$addId,
				"std" => "0",
				"fold" => 'slideshow_on'.$addId,
				"unfold" => 'rs_on'.$addId,
				"type" => "select2",
				"options"  => $galleries);
	
			$undsgn_options[] = array( "name" => "Flexslider aspect ratio",
				"desc" => "Choose the aspect ratio. 'Full' or ex. '16:9'",
				"id" => "aspect".$addId,
				"std" => "0",
				"fold" => 'slideshow_on'.$addId,
				"unfold" => 'rs_on'.$addId,
				"type" => "text");
	
			$undsgn_options[] = array( "name" => "",
				"desc" => "Choose the minimal height in pixel.",
				"id" => "minheight".$addId,
				"std" => "0",
				"fold" => 'slideshow_on'.$addId,
				"unfold" => 'rs_on'.$addId,
				"type" => "text");
	
			$undsgn_options[] = array( "name" => "Flexslider options",
				"desc" => "Animation",
				"id" => "sshow_effect".$addId,
				"std" => "0",
				"fold" => 'slideshow_on'.$addId,
				"unfold" => 'rs_on'.$addId,
				"type" => "select2",
				"options"  => $undsgn_options_slideshow_effects);
	
			$undsgn_options[] = array( "name" => "",
				"desc" => "Thumbnails.",
				"id" => "sshow_thumbs".$addId,
				"std" => "0",
				"fold" => 'slideshow_on'.$addId,
				"unfold" => 'rs_on'.$addId,
				"type" => "checkbox");
	
			$undsgn_options[] = array( "name" => "",
				"desc" => "Infinite loop.",
				"id" => "sshow_loop".$addId,
				"std" => "0",
				"fold" => 'slideshow_on'.$addId,
				"unfold" => 'rs_on'.$addId,
				"type" => "checkbox");
	
			$undsgn_options[] = array( "name" => "",
				"desc" => "Autoplay.",
				"id" => "sshow_auto".$addId,
				"std" => "0",
				"fold" => 'slideshow_on'.$addId,
				"unfold" => 'rs_on'.$addId,
				"type" => "checkbox");
	
			$undsgn_options[] = array( "name" => "",
				"desc" => "Height offset in pixel when fullscreen (this will reveal the section under).",
				"id" => "sshow_offset".$addId,
				"std" => "0",
				"fold" => 'slideshow_on'.$addId,
				"unfold" => 'rs_on'.$addId,
				"type" => "text");
	
			$undsgn_options[] = array( "name" => "Load more",
				"desc" => "Number of items to show at most.",
				"id" => "front_load_more".$addId,
				"type" => "text");
	
			$undsgn_options[] = array( "name" => "Automatic layout creation",
				"desc" => "Activate this option if you want to",
				"id" => "automatic".$addId,
				"std" => "0",
				"folds" => "1",
				"type" => "checkbox");

			$undsgn_options[] = array( "name" => "Category blog inclusion",
				"desc" => "List here the category ID you want to include and separate with a comma. Ex. '3,4,10,15'. NB. This can't be combined with Tags",
				"id" => "category_inclusion".$addId,
				"fold" => "automatic".$addId,
				"std" => "",
				"type" => "text");

			$undsgn_options[] = array( "name" => "Tag blog inclusion",
				"desc" => "List here the tag ID you want to include and separate with a comma. Ex. '3,4,10,15'. NB. This can't be combined with Categories",
				"id" => "tag_inclusion".$addId,
				"fold" => "automatic".$addId,
				"std" => "",
				"type" => "text");
	
			$undsgn_options[] = array( "name" => "Post exclusion",
				"desc" => "List here the post ID you want to exclude and separate with a comma. Ex. '3,4,10,15'",
				"id" => "post_exclusion".$addId,
				"fold" => "automatic".$addId,
				"std" => "",
				"type" => "text");

			$undsgn_options[] = array( "name" => "Category blog exclusion",
				"desc" => "List here the category ID you want to exclude and separate with a comma. Ex. '3,4,10,15'",
				"id" => "category_exclusion".$addId,
				"fold" => "automatic".$addId,
				"std" => "",
				"type" => "text");
	
			$undsgn_options[] = array( "name" => "Manual layout creator",
				"desc" => "<br />Load unlimited slides with drag and drop sortings.",
				"id" => "frontpage".$addId,
				"std" => "",
				"unfold" => 'automatic'.$addId,
				"type" => "frontpage");
			$i++;
		}
		
		$undsgn_options[] = array( "name" => "Layout",
			"type" => "heading");

		$undsgn_options[] = array( "name" => "Portfolio items visibility",
			"desc" => "Title",
			"id" => "portfolio_title",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Content",
			"id" => "portfolio_content",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Thumbnail",
			"id" => "portfolio_thumb",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "Gallery items visibility",
			"desc" => "Title",
			"id" => "gallery_title",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Content",
			"id" => "gallery_content",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Thumbnail",
			"id" => "gallery_thumb",
			"std" => "0",
			"type" => "checkbox");
			
		$undsgn_options[] = array( "name" => "",
			"desc" => "Image caption on rollover",
			"id" => "gallery_caption",
			"fold" => 'gallery_thumb',
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "Page items visibility",
			"desc" => "Title",
			"id" => "page_title",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Content",
			"id" => "page_content",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Thumbnail",
			"id" => "page_thumb",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "Blog items visibility",
			"desc" => "Title",
			"id" => "blog_title",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Content",
			"id" => "blog_content",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Thumbnail",
			"id" => "blog_thumb",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Date",
			"id" => "blog_date",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "",
			"desc" => "Author",
			"id" => "blog_author",
			"std" => "0",
			"type" => "checkbox");

		$undsgn_options[] = array( "name" => "Portfolio",
			"type" => "heading");

		$undsgn_options[] = array( "name" => "Portfolio additional info",
			"desc" => "",
			"id" => "pf_details",
			"std" => "0",
			"type" => "dynalist");

		$undsgn_options[] = array( "name" => "Contact",
			"type" => "heading");

		$undsgn_options[] = array( "name" => "Contact info",
			"desc" => "",
			"id" => "contact_details",
			"std" => "0",
			"type" => "dynalist");
		
		$undsgn_options[] = array( "name" => "Icons Installation",
			"type" => "heading");
			
		$undsgn_options[] = array( "name" => "Install Entypo Iconic Font",
			"desc" => "",
			"id" => "install_entypo",
			"std" => "",
			"type" => "iconic");

		$undsgn_options[] = array( "name" => "Custom Style and JS",
			"type" => "heading");

		$undsgn_options[] = array( "name" => "Custom CSS",
			"desc" => "Insert here your custom style sheet declarations.",
			"id" => "custom_css",
			"type" => "textarea");
			
		$undsgn_options[] = array( "name" => "Custom Javascript",
			"desc" => "Insert here your custom javascript code.",
			"id" => "custom_jscript",
			"type" => "textarea");

		$undsgn_options[] = array( "name" => "Footer",
			"type" => "heading");

		$undsgn_options[] = array( "name" => "Footer text",
			"desc" => "Insert here plain or HTML text for the last row of the footer.",
			"id" => "footer",
			"type" => "textarea");

		// Backup Options
		$undsgn_options[] = array( "name" => "Backup Options",
			"type" => "heading");

		$undsgn_options[] = array( "name" => "Backup and Restore Options",
			"id" => "undsgn_backup",
			"std" => "",
			"type" => "backup",
			"desc" => 'You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.',
		);

		$undsgn_options[] = array( "name" => "Transfer Theme Options Data",
			"id" => "undsgn_transfer",
			"std" => "",
			"type" => "transfer",
			"desc" => 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".
						',
		);


	}
}
?>
